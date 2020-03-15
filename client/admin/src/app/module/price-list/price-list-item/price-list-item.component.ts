import { Component, OnInit } from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import {ListData} from '../../../share/list-data';

@Component({
  selector: 'app-price-list-item',
  templateUrl: './price-list-item.component.html',
  styleUrls: ['./price-list-item.component.css']
})
export class PriceListItemComponent implements OnInit {
    public ld;
    public  url;
    public data = {
        product_id: null,
        grade_id: null,
        priceItem: []
    };
    public fd;
    public priceList;
    public productList: any = [];
    public gradeList: any = [];
    public listPriceItem: any = [];

    constructor(public app: AppService, private route: ActivatedRoute) { }

    ngOnInit() {
        this.fd = new FormData(this.data);
        this.app.get('price-lists', {id :  this.route.snapshot.params['id']}).subscribe((res: any) => {
            this.priceList = res.data[0].is_default;
        });
        const url = 'price-list-item/' + this.route.snapshot.params['id'];
        this.ld = new ListData(this.app, this.route, url);
        this.dataProduct();
    }

    /*
   * get all Product
   *
   * */
    dataProduct() {
        const dataQuery = {sort: 'id', direction: 'desc', active: this.app.constant.ACTIVE_TRUE};
        this.app.get('products', dataQuery).subscribe((res: any) => {
            this.productList = res.data;
        });
    }

    /*
    * change value product
    * */
    changeValueProduct(e) {
        // tslint:disable-next-line:radix
        const data = this.productList.find(x => x.id === parseInt(e.target.value));
        const url = 'grade-about-group/' + data.grade_group_id;
        this.app.get(url).subscribe((res: any) => {
           this.gradeList = res.data;
        });

    }

    savePriceItem() {
        if (this.fd.form.value.product_id == null) {
            alert('Select product');
            return false;
        } else if (this.fd.form.value.grade_id == null) {
            alert('Select grade');
            return false;
        }

        // tslint:disable-next-line:radix
        const dataProduct = this.productList.find(x => x.id === parseInt(this.fd.form.value.product_id));
        // tslint:disable-next-line:radix
        const dataGrade = this.gradeList.find(x => x.id === parseInt(this.fd.form.value.grade_id));
        this.addListArray(this.listPriceItem, dataProduct, dataGrade)
        this.fd.form.controls['product_id'].patchValue(null);
        this.fd.form.controls['grade_id'].patchValue(null);
    }

    addListArray(arr, dataProduct, dataGrade) {
        const found = arr.some(el => el.grade_id === dataGrade.id && el.product_id === dataProduct.id);
        if (!found) {
            arr.push({
                   'price_list_id' : this.route.snapshot.params['id'],
                   'product_id' : dataProduct.id,
                   'product_display_name' : dataProduct.display_name,
                   'grade_id' : dataGrade.id,
                   'grade_display_name'  : dataGrade.display_name,
                    'unit_price' : 0
            });
        }
    }

    changeUnitPrice(index, event) {
        this.listPriceItem[index].unit_price = event.target.value;
    }

    removListItem(index) {
        this.listPriceItem.splice(index, 1);
    }


    submitPriceListItem() {
       if (this.listPriceItem.length === 0) {
           alert('Select Product and Grade');
           return false;
       }
       this.fd.form.value.priceItem = this.listPriceItem;

       this.app.post('price-list-item', this.fd.form.value).subscribe(res => {
           const url = 'price-list-item/' + this.route.snapshot.params['id'];
           this.ld = new ListData(this.app, this.route, url);
       });
       this.listPriceItem = [];
    }

    cancelPrice() {
        this.listPriceItem = [];
    }

    formatNumber(number) {
        return number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }

    removeItemPrice(item) {
        if (confirm('Are you sure you delete the price list item')) {
            this.fd.form.value.product_id = item.product_id;
            this.fd.form.value.grade_id = item.grade_id;
            this.app.post('price-list-item-delete', this.fd.form.value).subscribe(res => {
                alert('Remove success price list item!');
                const url = 'price-list-item/' + this.route.snapshot.params['id'];
                this.ld = new ListData(this.app, this.route, url);
            });
        }
    }

}
