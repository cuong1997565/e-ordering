import {Component, ElementRef, OnInit, ViewChild, AfterViewInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import * as moment from 'moment';
import * as $ from 'jquery';

declare var $: any;
import * as _ from 'lodash';


@Component({
    selector: 'app-form',
    templateUrl: './form.component.html',
    styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit, AfterViewInit {
    @ViewChild('image_change') imageRef: ElementRef;
    public fd;
    public url;
    public featureItem: any = [];
    public listCategory;
    public listProductType: any = [];
    public listUom: any = [];
    public listGradeGroup: any = [];
    public isMaxAge = false;
    public listPriceList: any = [];
    public listStore: any = [];
    public listGrade: any = [];
    public listParentCategories: any = [];
    public selectedFile: File = null;
    public productStore = {};
    public priceListItem: any = [];
    public chips = {};
    private data = {
        id: '',
        code: '',
        image: '',
        image_edit: '',
        featureitem_id: null,
        featureitem: {},
        product_type_id: null,
        uom_id: null,
        category_id: null,
        grade_group_id: null,
        short_name: '',
        display_name: '',
        is_life_management: 0,
        max_age: 0,
        release_date: moment().format('YYYY-MM-DD'),
        active: 0,
        store_id: null,
        stores: {},
        pricelist_id: null,
        grade_id: null,
        pricelistitem: []
    };

    constructor(
        public app: AppService,
        private route: ActivatedRoute,
        private router: Router,
    ) {
    }

    // change image
    onFileChange(event) {
        this.selectedFile = <File>event.target.files[0];
        const reader = new FileReader();
        reader.onload = function () {
            let image_change = this.imageRef.nativeElement;
            image_change.src = reader.result;
        }.bind(this);
        reader.readAsDataURL(event.target.files[0]);
    }

    ngOnInit() {
        this.dataCategory();
        this.dataFeatureItem();
        this.dataProductType();
        this.dataGradeGroup();
        this.dataUom();
        this.dataStore();
        this.dataPriceList();
        this.dataGradeList();
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_PRODUCT]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_PRODUCT]) {
            this.router.navigate(['dashboard']);
        }
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('product/detail', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.data = res.data[0];
                this.data.release_date = moment(this.data.release_date).format('YYYY-MM-DD');
                if (res.data[0].is_life_management = 1) {
                    this.isMaxAge = true;
                } else {
                    this.isMaxAge = false;
                }
                this.fd.setData(res.data[0]);
                /*
                * get featureitem
                * */
                if (res.data[0].featureitem.length > 0) {
                    for (let i = 0; i < res.data[0].featureitem.length; i++) {
                        // tslint:disable-next-line:radix
                        this.chips[res.data[0].featureitem[i].id] = {
                            id: res.data[0].featureitem[i].id,
                            name: res.data[0].featureitem[i].name
                        };
                    }
                }
                /*
                * get stores
                * */
                if (res.data[0].productstore.length > 0) {
                    for (let i = 0; i < res.data[0].productstore.length; i++) {
                        // tslint:disable-next-line:radix
                        this.productStore[res.data[0].productstore[i].id] = {
                            id: res.data[0].productstore[i].id,
                            name: res.data[0].productstore[i].name
                        };
                    }
                }
                /*
                * price list item
                * */
                if (res.data[0].price_list_items.length > 0) {
                    for (let i = 0; i < res.data[0].price_list_items.length; i++) {
                        this.priceListItem.push({
                            'price_name': res.data[0].price_list_items[i].price_list.name,
                            'price_list_id': res.data[0].price_list_items[i].price_list.id,
                            'grade_name': res.data[0].price_list_items[i].grade.display_name,
                            'grade_id': res.data[0].price_list_items[i].grade.id,
                            'unit_price': res.data[0].pricelistitem[i].pivot.unit_price
                        });
                    }
                }
            });
        }
    }

    ngAfterViewInit() {
        $('.datepicker').datepicker('destroy');
        $('#ui-datepicker-div').remove();
        const self = this;
        $('.from_date').datepicker({
            prevText: '<<',
            nextText: '>>',
            dateFormat: 'yy-mm-dd',
        });
        $('.from_date').datepicker('setDate', this.data.release_date);
        $('.from_date').datepicker('option', 'minDate', this.data.release_date);
    }


    /*
    * get all category
    * */
    dataCategory() {
        const dataQuery = {sort: 'name', direction: 'asc'};
        this.app.get('categories-about-product', dataQuery).subscribe((res: any) => {
            this.listCategory = res.data;
            const newArray = [];
            _.forEach(this.listCategory, function (value, index) {
                newArray.push(value);
                if (value.category_level_tow.length > 0) {
                    _.forEach(value.category_level_tow, function (c2, index_c2) {
                        newArray.push(c2);
                        if (c2.category_level_three.length > 0) {
                            _.forEach(c2.category_level_three, function (c3, index_c3) {
                                newArray.push(c3);
                                if (c3.category_level_four.length > 0) {
                                    _.forEach(c3.category_level_four, function (c4, index_c4) {
                                        newArray.push(c4);
                                    });
                                }
                            });
                        }
                    });
                }
            });
            this.listParentCategories = newArray;
        });
    }

    /*
     * get all Feature item
    * */

    dataFeatureItem() {
        const dataQuery = {sort: 'id', direction: 'desc', active: this.app.constant.ACTIVE_TRUE};
        this.app.get('feature-items-about-product', dataQuery).subscribe((res: any) => {
            this.featureItem = res.data;
        });
    }

    /*
    * get all Product Type
    *
    * */
    dataProductType() {
        const dataQuery = {sort: 'id', direction: 'desc', active: this.app.constant.ACTIVE_TRUE};
        this.app.get('product-type-about-product', dataQuery).subscribe((res: any) => {
            this.listProductType = res.data;
        });
    }

    /*
    * get all uom
    * **/
    dataUom() {
        const dataQuery = {
            sort: 'id', direction: 'desc', active: this.app.constant.ACTIVE_TRUE,
            is_based_uom: this.app.constant.IS_BASED_UOM_TRUE
        };
        this.app.get('uoms', dataQuery).subscribe((res: any) => {
            this.listUom = res.data;
        });
    }

    /*
    * get all grade group
    * */
    dataGradeGroup() {
        const dataQuery = {sort: 'id', direction: 'desc', active: 1};
        this.app.get('grade-group-about-product', dataQuery).subscribe((res: any) => {
            this.listGradeGroup = res.data;
        });
    }

    /*
    * get all store
    * */
    dataStore() {
        const dataQuery = {sort: 'id', direction: 'desc', active: this.app.constant.ACTIVE_TRUE};
        this.app.get('product-stores', dataQuery).subscribe((res: any) => {
            this.listStore = res.data;
        });
    }

    /*
    * get all data price list
    * */
    dataPriceList() {
        const dataQuery = {
            sort: 'id', direction: 'desc', active: this.app.constant.ACTIVE_TRUE,
            is_default: this.app.constant.ACTIVE_TRUE
        };
        this.app.get('price-list-items', dataQuery).subscribe((res: any) => {
            this.fd.form.controls['pricelist_id'].patchValue(res.data[0].id);
            this.listPriceList = res.data;
        });
    }

    /*
   * get all data price list
   * */
    dataGradeList() {
        const dataQuery = {sort: 'id', direction: 'desc', active: this.app.constant.ACTIVE_TRUE};
        this.app.get('grade-items', dataQuery).subscribe((res: any) => {
            this.listGrade = res.data;
        });
    }

    /*
    * open dialog feature item
    * */
    openDialogFeatureItem() {
        if (this.fd.form.value.featureitem_id > 0) {
            this.fd.form.controls['featureitem_id'].patchValue(null);
        }
    }

    /*
    * save feature item
    * */
    saveFeatureItem() {
        if (this.fd.form.value.featureitem_id > 0) {
            // tslint:disable-next-line:radix
            const array = this.featureItem.find(x => x.id === parseInt(this.fd.form.value.featureitem_id));
            this.chips[array.id] = {
                id: array.id,
                name: array.name
            };
            $('#exampleModal').modal('hide');
        } else {
            alert('Select feature item');
        }
    }

    /*
    * open dialog Store
    * */
    openDialogStore() {
        this.fd.form.controls['store_id'].patchValue(null);
    }

    /*
    * save store
    * */
    saveStore() {
        if (this.fd.form.value.store_id > 0) {
            // tslint:disable-next-line:radix
            const array = this.listStore.find(x => x.id === parseInt(this.fd.form.value.store_id));
            this.productStore[array.id] = {
                id: array.id,
                name: array.name,
                code: array.code
            };
            $('#productStoreModal').modal('hide');

        } else {
            alert('Select store');
        }
    }

    addPriceListItem() {
        if (this.fd.form.value.pricelist_id === null) {
            alert('Select price list');
            return false;
        }

        if (this.fd.form.value.grade_id === null) {
            alert('Select grade list');
            return false;
        }

        // tslint:disable-next-line:radix
        const arrayPriceList = this.listPriceList.find(x => x.id === parseInt(this.fd.form.value.pricelist_id));
        // tslint:disable-next-line:radix
        const arrayGrade = this.listGrade.find(x => x.id === parseInt(this.fd.form.value.grade_id));
        this.addListArray(this.priceListItem, arrayPriceList, arrayGrade);
        this.fd.form.controls['pricelist_id'].patchValue(null);
        this.fd.form.controls['grade_id'].patchValue(null);
    }

    addListArray(arr, arrayPriceList, arrayGrade) {
        const found = arr.some(el => el.price_list_id === arrayPriceList.id && el.grade_id === arrayGrade.id);
        if (!found) {
            this.priceListItem.push({
                'price_name': arrayPriceList.name,
                'price_list_id': arrayPriceList.id,
                'grade_name': arrayGrade.display_name,
                'grade_id': arrayGrade.id,
                'unit_price': 0
            });
        }
    }


    //remove chip
    removeChip(id) {
        delete this.chips[id];
    }

    /*
    * remove store
    * */
    removeStore(id) {
        delete this.productStore[id];
    }

    changeUnitPrice(index, event) {
        this.priceListItem[index].unit_price = event.target.value;
    }

    /*
    * remove price list item
    * */
    removePriceListItem(index) {
        this.priceListItem.splice(index, 1);

    }

    changeStatus(event) {
        if (event.target.checked) {
            this.isMaxAge = true;
        } else {
            this.isMaxAge = false;
        }
    }

    save() {
        delete this.fd.form.value.featureitem_id;
        delete this.fd.form.value.store_id;
        this.fd.form.value.featureitem = JSON.stringify(Object.keys(this.chips));
        this.fd.form.value.stores = JSON.stringify(Object.keys(this.productStore));
        this.fd.form.value.pricelistitem = this.priceListItem;
        if (this.route.snapshot.params['id']) {
            this.url = 'products/' + this.route.snapshot.params['id'];
            // this.fd.form.value.image = this.selectedFile;
            if (this.selectedFile !== null) {
                this.fd.form.value.image = this.selectedFile;
            }

            this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Product has been saved');
                return this.router.navigate(['/product']);
            });
        } else {
            this.fd.form.value.image = this.selectedFile;
            this.app.post('products', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Product has been saved');
                return this.router.navigate(['/product']);
            });
        }
    }

}
