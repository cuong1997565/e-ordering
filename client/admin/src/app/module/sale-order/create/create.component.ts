import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import * as $ from 'jquery';
import { ListData } from '../../../share/list-data';
import {Location} from '@angular/common';

import * as _ from 'lodash';
import {FormData} from '../../../share/form-data';
import {element} from 'protractor';
import * as moment from 'moment';
declare var $: any;


@Component({
    selector: 'app-create',
    templateUrl: './create.component.html',
    styleUrls: ['./create.component.css']
})
export class CreateComponent implements OnInit {

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router, private location: Location) {
    }

    public fd;
    public ulr;
    public ld;
    public code;
    public listFactory = [];
    public listProductSelect: any = [];
    public listCreditAccountAndDistributor = [];
    public productDetail: any = [];
    public dataListGrade: any = [];
    public dataListAttribute: any = [];
    public dataListUom: any = [];
    public arrayAttributeList: any = [];
    public priceList: any = [];
    public arrProduct: any = [];
    public listSku: any = [];
    public arraySaleOrder: any = {};
    public E_Amount = 0;
    public factory_id = 0;
    public paginateFactory = 0;
    public nameAccount = '';
    public disabled_factory = false;
    public disabled_submit = true;
    public inputAmount = 1;
    public inputGrade = null;
    public inputUom = null;
    public isInputAmount = false;
    public isInputGrade = false;
    public isInputUom = false;
    private data = {
        id: '',
        name: '',
        email: '',
        address: '',
        phone: '',
        area_id: null,
        distributor_id: null,
        sale_id : 1,
        price_id: null,
        code: '',
        tax_code: '',
        contact_person: '',
        active: 0,
        notes: ''
    };

    ngOnInit() {
        // let self = this;
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_SO_ORDER]) {
            this.router.navigate(['dashboard']);
        }
        this.nameAccount = this.app.curUser.name;
        this.fd.form.value.sale_id = this.app.curUser.id;
        this.factory();
        this.getPriceList();
        this.creditAccountAndDistributor();
    }

    factory() {
        this.app.get('factories', {active: this.app.constant.ACTIVE_TRUE}).subscribe((res: any) => {
            this.listFactory = res.data;

        });
    }

    creditAccountAndDistributor() {
        this.app.get('credit-account-distributor').subscribe((res: any) => {
            this.listCreditAccountAndDistributor = res.data;
        });
    }

    changeFactory($event) {
        this.factory_id = $event.target.value;
        this.fd.form.value.factory_id =  $event.target.value;
    }

    Product() {
        this.paginateFactory = this.factory_id;
        if (this.paginateFactory > 0) {
            const dataQuery = {
                'limit' : 4
            };
            const url = 'factories/' + this.paginateFactory + '/products/search';
            const removepage = new URL(this.removeParam('page', window.location.href));
            removepage.searchParams.set('page', '1');

            let pathName = removepage.pathname;
            this.location.go(pathName);


            if (removepage.pathname.indexOf('/admin') === 0) {
                pathName = removepage.pathname.replace('/admin', '');
            }
            this.router.navigateByUrl(pathName);

            this.listProductSelect = new ListData(this.app, this.route, url , dataQuery);

        } else {
            alert('Please choose factory and product !');
        }
    }

    // remove page on params
    removeParam(key, sourceURL) {
        // @ts-ignore

        const rtn = sourceURL.split('?')[0],
            // @ts-ignore
            param,
            params_arr = [],
            queryString = (sourceURL.indexOf('?') !== -1) ? sourceURL.split('?')[1] : '';
        if (queryString !== '') {
            // @ts-ignore
            params_arr = queryString.split('&');
            for (let i = params_arr.length - 1; i >= 0; i -= 1) {
                // @ts-ignore
                param = params_arr[i].split('=')[0];
                if (param === key) {
                    params_arr.splice(i, 1);
                }
            }
            // @ts-ignore
            rtn = rtn + '?' + params_arr.join('&');
        }
        return rtn;
    }

    slideString(name) {
        let  data = '';
        if (name.length > 10) {
            data =  name.slice(0, 10) + '...';
        } else {
            data = name;
        }
        return data;

    }
    getPriceList() {
        this.app.get('price-lists').subscribe((res: any) => {
            this.priceList = res.data;
            this.fd.form.controls['price_id'].patchValue(res.data[0].id);
        });
    }
    /*
    *get detail product
    *  */
    getDetalProduct(idProduct, idFactory) {
        this.inputAmount = 1;
        this.inputGrade = null;
        this.inputUom = null;
        this.arrayAttributeList = [];
        if (idProduct > 0 && idFactory > 0) {
            const url = 'products/' + idProduct + '/detail/' + idFactory;
            this.app.get(url).subscribe((res: any) => {
                this.productDetail = res.data;
                this.listGrade();
                this.listUom();
                this.listAttribute();
            });
        }
    }

    /*
    * list grade about product grade_group
    * */
    listGrade() {
        const url = 'grade-items/' + this.productDetail.grade_group_id;
        // @ts-ignore
        return this.app.get(url).subscribe((res: any) => {
            this.dataListGrade = res.data;
        });
    }
    /*
    * list attribute
    * */
    listAttribute() {
        const url = 'attribute-item/' + this.productDetail.product_type_id;
        return this.app.get(url).subscribe((res: any) => {
            this.dataListAttribute = res.data;
            if (res.data.length > 0) {
                for (let i = 0; i < res.data.length; i++) {
                    this.arrayAttributeList.push({
                        'id' : res.data[i].id,
                        'attribute_label' : null,
                        'name': res.data[i].name,
                        'code' : res.data[i].code,
                        'sequence' : res.data[i].sequence,
                        'type' : res.data[i].type,
                        'attribute_name' : null
                    });
                }
            }
        });
    }
    /*
       * list uom
    * */
    listUom() {
        const url = 'uom/' + this.productDetail.uom_id;
        return this.app.get(url).subscribe((res: any) => {
            this.dataListUom = res.data;
        });
    }


    /*check input type number*/
    IsNumeric(input) {
        return /[0-9]|\./.test(input);
    }

    getValueAttribute(event, index) {
        this.arrayAttributeList[index].attribute_label = event.target.value;
        if (this.arrayAttributeList[index].type === this.app.constant.Attributes_Type_List) {
            // get value attribute
            // tslint:disable-next-line:radix no-shadowed-variable
            const attribute = this.dataListAttribute[index].attributelist.find(element => element.id === parseInt(event.target.value));
            this.arrayAttributeList[index].attribute_name = attribute.value;
        }


    }

    /*
    * change sale note
    * */
    changeSaleNote(index, event) {
        this.arrProduct[index].sale_note = event.target.value;
    }

    changeQuantity(index, event) {
        if (event.target.value === '') {
            this.arrProduct[index].sale_quantity = 1;
            $('#sale-quantity-' + index).val(1);
        } else if (event.target.value <= 0) {
            this.arrProduct[index].sale_quantity = 1;
            $('#sale-quantity-' + index).val(1);
        } else {
            this.arrProduct[index].sale_quantity = event.target.value;
        }

        this.arrProduct[index].amount = this.arrProduct[index].unit_price * this.arrProduct[index].sale_quantity;

        this.arrProduct[index].dataCheckAmount[9] = this.arrProduct[index].sale_quantity;

        this.E_Amount = _.sumBy(this.arrProduct, function (o) {
            return o.amount;
        });
    }

    changePriceList(e) {
        if (this.arrProduct.length > 0) {
            // this.price_list_id = e.target.value;
            const url = 'price-list/' + e.target.value;
            const self = this;
            this.app.get(url).subscribe((data: any) => {
                _.forEach(data.data[0].price_list_items, function (value, index) {
                    _.forEach(self.arrProduct, function (val, i) {
                        // product id có trong price item
                        if (value.product_id === val.product_id) {

                            self.arrProduct[i].unit_price = value.unit_price;

                            self.arrProduct[i].amount = value.unit_price * self.arrProduct[i].sale_quantity;

                            self.arrProduct[i].unit_price_id = value.price_list_id;

                        } else  {
                            //  product id có trong price default item
                            // tslint:disable-next-line:no-shadowed-variable
                            const priceListIsDefault = self.priceList.find(element => element.is_default
                                === self.app.constant.PRICE_DEFAULT_TRUE);

                            const price_list_items = priceListIsDefault.price_list_items.find
                            // tslint:disable-next-line:radix
                            (ele => ele.product_id === parseInt(val.product_id));

                            self.arrProduct[i].unit_price = price_list_items.unit_price;

                            self.arrProduct[i].amount = price_list_items.unit_price * self.arrProduct[i].sale_quantity;
                        }
                    });
                });
                this.E_Amount = _.sumBy(self.arrProduct, function (o) {
                    return o.amount;
                });
            });
        }
    }

    removePriceListItem(index) {
        this.arrProduct.splice(index, 1);
        if (this.arrProduct.length === 0) {
            this.disabled_factory = false;
            this.disabled_submit = true;
        }
    }


    /*
    * add product move array
    * */
     onSelectProduct(productDetail) {
        if (!this.IsNumeric(this.inputAmount)) {
            this.isInputAmount = true;
            return  false;
        } else {
            this.isInputAmount = false;
        }

        if (this.inputAmount < 0) {
            this.isInputAmount = true;
            return  false;
        } else {
            this.isInputAmount = false;
        }

        if (this.inputGrade === null) {
            this.isInputGrade = true;
            return  false;
        } else {
            this.isInputGrade = false;
        }

        if (this.inputUom === null) {
            this.isInputUom = true;
            return  false;
        } else {
            this.isInputUom = false;
        }

        for (let i = 0; i < this.arrayAttributeList.length; i++) {
            // tslint:disable-next-line:radix
            if (this.arrayAttributeList[i].type === parseInt(this.app.constant.Attributes_Type_List)) {
                if (this.arrayAttributeList[i].attribute_label === null || this.arrayAttributeList[i].attribute_label === 'null') {
                    $('.error-attribute-' + i).html('Please select a attributes');
                    return false;
                } else {
                    $('.error-attribute-' + i).html('');
                }
            }
            // tslint:disable-next-line:radix
            if (this.arrayAttributeList[i].type === parseInt(this.app.constant.Attributes_Type_Number)) {
                if (this.arrayAttributeList[i].attribute_label == null) {
                    $('.error-attribute-' + i).html('Please enter input');
                    return  false;
                } else if (!this.IsNumeric(this.arrayAttributeList[i].attribute_label)) {
                    $('.error-attribute-' + i).html('Input field is invalid.');
                    return false;
                } else if (this.arrayAttributeList[i].attribute_label < 0) {
                    $('.error-attribute-' + i).html('Input field is invalid.');
                    return false;
                } else {
                    $('.error-attribute-' + i).html('');
                }
            }

            // tslint:disable-next-line:radix
            if (this.arrayAttributeList[i].type === parseInt(this.app.constant.Attributes_Type_String)) {
                if (this.arrayAttributeList[i].attribute_label == null || this.arrayAttributeList[i].attribute_label === '') {
                    $('.error-attribute-' + i).html('Please enter input');
                    return  false;
                } else {
                    $('.error-attribute-' + i).html('');
                }
            }
        }
        // sort sequence about array attribute
        this.arrayAttributeList.sort((a, b) => {
            if (a.sequence < b.sequence) {
                return -1;
            }
            if (a.sequence > b.sequence) {
                return 1;
            }
            return 0;
        });

        let data  = productDetail.code;

        // tslint:disable-next-line:radix no-shadowed-variable
        const uom = this.dataListUom.find(element => element.id === parseInt(this.inputUom));
        // tslint:disable-next-line:radix no-shadowed-variable
        const grade = this.dataListGrade.find(element => element.id === parseInt(this.inputGrade));

        // tslint:disable-next-line:radix no-shadowed-variable
        var priceList = this.priceList.find(element => element.id === parseInt(this.fd.form.value.price_id));

        var price_list_items = priceList.price_list_items.find(ele => ele.product_id === parseInt(productDetail.id));

        if (price_list_items === undefined) {
            var priceListIsDefault = this.priceList.find(element => element.is_default === 1);

            var price_list_items = priceListIsDefault.price_list_items.find(ele => ele.product_id === parseInt(productDetail.id));
        }
        // tslint:disable-next-line:no-shadowed-variable

        for (let i = 0 ; i < this.arrayAttributeList.length; i++) {
            data  +=  '.' + this.arrayAttributeList[i].code;
        }

        // create code sku check stock
        const dataCheckAmount = new Array();
        dataCheckAmount.push(productDetail.store_code);

        var codeCheckAmount = productDetail.store_code.substring(0, 2);
        codeCheckAmount += '.' + this.arrayAttributeList[0].attribute_name + '.' + '09' + productDetail.code;
        dataCheckAmount.push(codeCheckAmount);

        // push attribute data check amount
        for (let i = 1 ; i < this.arrayAttributeList.length; i++) {
            if (this.arrayAttributeList[i].type === this.app.constant.Attributes_Type_List) {
                dataCheckAmount.push(this.arrayAttributeList[i].attribute_name);
            } else  {
                const attribute_label = this.arrayAttributeList[i].attribute_label !== null
                    ? this.arrayAttributeList[i].attribute_label : '';
                dataCheckAmount.push(attribute_label);

            }
        }

        // xu ly date release
        const d = productDetail.release_date.split('-');
        const d2 = d[2].split(' ');
        const relese_date = d2[0] + d[1] + d[0].toString().substr(-2)
        dataCheckAmount.push(relese_date);

        dataCheckAmount.push('CH');


        /// Mã đơn vị tính
        if (uom.based_uom_id === null) {
            dataCheckAmount.push(uom.code);
        } else {
            // tslint:disable-next-line:no-shadowed-variable radix
            const based_uom = this.dataListUom.find(element => element.id === parseInt(uom.based_uom_id));
            dataCheckAmount.push(based_uom.code);
        }

        // so luong
        dataCheckAmount.push(this.inputAmount);

        dataCheckAmount.push(this.app.constant.CODE_PRIME);



        this.app.post('product/list_check_barcode', {dataCheckBarcode : dataCheckAmount})
            .subscribe((res: any) => {
                const listSku = JSON.parse(res.data.sku_list);
                if (listSku.length > 0) {
                   this.listSku = JSON.parse(res.data.sku_list);
                   this.pushArrayProduct(this.arrayAttributeList, data, productDetail.id,  productDetail.image,
                       productDetail.display_name, this.inputGrade, this.inputUom, uom.display_name, grade.name,
                       productDetail.code, this.inputAmount, this.fd.form.value.price_id, price_list_items.unit_price,
                       price_list_items.unit_price *  this.inputAmount, null, this.app.constant.SO_OPEN,
                       dataCheckAmount, this.listSku[0].SoTonQD);

                } else {
                    this.pushArrayProduct(this.arrayAttributeList, data, productDetail.id,  productDetail.image,
                        productDetail.display_name, this.inputGrade, this.inputUom, uom.display_name, grade.name,
                        productDetail.code, this.inputAmount, this.fd.form.value.price_id, price_list_items.unit_price,
                        price_list_items.unit_price *  this.inputAmount, null, this.app.constant.SO_OPEN,
                        dataCheckAmount, 'Mã Sku không tồn tại trong hệ thống');
                }
            }, (errr) => {
                console.log(errr);
                this.pushArrayProduct(this.arrayAttributeList, data, productDetail.id,  productDetail.image,
                    productDetail.display_name, this.inputGrade, this.inputUom, uom.display_name, grade.name,
                    productDetail.code, this.inputAmount, this.fd.form.value.price_id, price_list_items.unit_price,
                    price_list_items.unit_price *  this.inputAmount, null, this.app.constant.SO_OPEN,
                    dataCheckAmount, 'Mã Sku không tồn tại trong hệ thống');
            });

        // // console.log(productDetail);
        this.disabled_factory = true;
        this.disabled_submit = false;
        $('#myModal').modal('hide');
        return  true;

    }
    // push array product
    pushArrayProduct(product_attributes, code, product_id, image, display_name, grade_id, uom_id,
                     uom_name, grade_name, code_product, sale_quantity, unit_price_id, unit_price, amount,
                     sale_note, status, dataCheckAmount, SoTonQD) {
        this.arrProduct.push({
            'product_attributes' : product_attributes,
            'code' : code,
            'product_id' : product_id,
            'image' : image,
            'display_name' : display_name,
            'grade_id' : grade_id,
            'uom_id' : uom_id,
            'uom_name' : uom_name,
            'grade_name' : grade_name,
            'code_product' : code_product,
            'sale_quantity' : sale_quantity,
            'unit_price_id' : unit_price_id,
            'unit_price' : unit_price,
            'amount' : amount,
            'sale_note' : sale_note,
            'status' : status,
            'dataCheckAmount' : dataCheckAmount,
            'SoTonQD' : SoTonQD
        });

        this.E_Amount = _.sumBy(this.arrProduct, function (o) {
            return o.amount;
        });

    }

    save() {
        if (this.fd.form.value.distributor_id === null) {
            alert('Please choose distributor !');
            return false;
        }

        this.arraySaleOrder = {
            'order_id' : 1,
            'distributor_id' : this.fd.form.value.distributor_id,
            'factory_id' : this.factory_id,
            'price_list_id' : this.fd.form.value.price_id,
            'estimated_amount' : this.E_Amount,
            'sale_person_id' :  this.fd.form.value.sale_id,
            'status' : this.app.constant.SO_OPEN,
            'note' : this.fd.form.value.notes,
            'items' : [],
            'item_orders' : []
        };
        if (this.arraySaleOrder.items.length === 0) {
            // tslint:disable-next-line:forin
            for (const productIndex in this.arrProduct) {
                const newOrderProduct = {};
                newOrderProduct['product_attributes'] = this.arrProduct[productIndex].product_attributes;
                newOrderProduct['product_id'] = this.arrProduct[productIndex].product_id;
                newOrderProduct['grade_id'] = this.arrProduct[productIndex].grade_id;
                newOrderProduct['uom_id'] = this.arrProduct[productIndex].uom_id;
                newOrderProduct['sale_quantity'] = this.arrProduct[productIndex].sale_quantity;
                newOrderProduct['unit_price_id'] = this.arrProduct[productIndex].unit_price_id;
                newOrderProduct['unit_price'] = this.arrProduct[productIndex].unit_price;
                newOrderProduct['amount'] = this.arrProduct[productIndex].amount;
                newOrderProduct['sale_note'] = this.arrProduct[productIndex].sale_note;
                newOrderProduct['status'] = this.arrProduct[productIndex].status;
                newOrderProduct['code'] = this.arrProduct[productIndex].code;
                newOrderProduct['factory_id'] = this.factory_id;
                newOrderProduct['distributor_id'] = this.arraySaleOrder.distributor_id;
                newOrderProduct['dataCheckAmount'] = this.arrProduct[productIndex].dataCheckAmount;

                this.arraySaleOrder.items.push(newOrderProduct);
                this.arraySaleOrder.item_orders.push(newOrderProduct);
            }
        }


        this.app.post('sale_orders', {
            'order_id' : this.arraySaleOrder.order_id,
            'distributor_id' : this.arraySaleOrder.distributor_id,
            'factory_id' : this.arraySaleOrder.factory_id,
            'price_list_id' : this.arraySaleOrder.price_list_id,
            'estimated_amount' : this.arraySaleOrder.estimated_amount,
            'sale_person_id' :  this.arraySaleOrder.sale_person_id,
            'so_date' :  moment().format('YYYY-MM-DD'),
            'status' : this.arraySaleOrder.status,
            'note' : this.arraySaleOrder.note,
            'items' : this.arraySaleOrder.items,
            'item_orders' : this.arraySaleOrder.item_orders
        }).subscribe((res) => {
            this.router.navigate([`/sale-order`]);
            this.app.flashSuccess('Sale Order has been saved');
        });
    }
}
