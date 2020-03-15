import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../share/share.module';
import { BrandRoutingModule } from './brand-routing.module';

@NgModule({
    imports: [
        CommonModule,
        ReactiveFormsModule,
        ShareModule,
        BrandRoutingModule
    ],
    declarations: [ListComponent, FormComponent]
})
export class BrandModule { }
