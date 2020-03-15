import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {ContactRoutingModule} from './contact-routing.module';
import {ShareModule} from '../../share/share.module';
import {FormComponent} from './form/form.component';
import {ReactiveFormsModule} from '@angular/forms';

@NgModule({
    imports: [
        CommonModule,
        ContactRoutingModule,
        ShareModule,
        ReactiveFormsModule
    ],
    declarations: [FormComponent]
})
export class ContactModule {
}
