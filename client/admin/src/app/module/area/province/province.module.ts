import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {ProvinceRoutingModule} from './province-routing.module';
import {ListComponent} from './list/list.component';
import {FormComponent} from './form/form.component';
import {ShareModule} from "../../../share/share.module";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";

@NgModule({
    imports: [
        CommonModule,
        ProvinceRoutingModule,
        ShareModule,
        ReactiveFormsModule
    ],
    declarations: [ListComponent, FormComponent]
})
export class ProvinceModule {
}
