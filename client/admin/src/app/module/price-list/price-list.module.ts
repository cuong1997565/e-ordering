import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../share/share.module';
import { PriceListRoutingModule } from './price-list-routing.module';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { PriceListItemComponent } from './price-list-item/price-list-item.component';


@NgModule({
    imports: [
        CommonModule,
        ReactiveFormsModule,
        ShareModule,
        PriceListRoutingModule
    ],
    declarations: [ListComponent, FormComponent, PriceListItemComponent]
})

export class PriceListModule { }
