import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {StaffRoutingModule} from './staff-routing.module';
import {ShareModule} from '../../share/share.module';
import {FormComponent} from './form/form.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ListComponent} from './list/list.component';

@NgModule({
    imports: [
        CommonModule,
        StaffRoutingModule,
        ShareModule,
        ReactiveFormsModule,
        FormsModule
    ],
    declarations: [FormComponent, ListComponent]
})
export class StaffModule {
}
