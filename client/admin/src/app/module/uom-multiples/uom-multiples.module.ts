import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { UomMultiplesRoutingModule } from './uom-multiples-routing.module';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../share/share.module';

@NgModule({
    imports: [
        CommonModule,
        UomMultiplesRoutingModule,
        ReactiveFormsModule,
        ShareModule
    ],
    declarations: [ListComponent, FormComponent]
})
export class UomMultiplesModule { }
