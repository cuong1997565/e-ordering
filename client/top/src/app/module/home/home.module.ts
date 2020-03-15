import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {HomeRoutingModule} from './home-routing.module';
import {ListComponent} from './list/list.component';
import {ShareModule} from '../../share/share.module';
import {ProductComponent} from './product/product.component';
import {ReactiveFormsModule} from '@angular/forms';

@NgModule({
    imports: [
        CommonModule,
        HomeRoutingModule,
        ShareModule,
        ReactiveFormsModule
    ],
    declarations: [
        ListComponent,
        ProductComponent,
    ],
})
export class HomeModule {
}
