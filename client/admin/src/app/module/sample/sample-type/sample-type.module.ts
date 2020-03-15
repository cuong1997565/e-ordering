import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SampleTypeRoutingModule } from './sample-type-routing.module';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { ShareModule } from "../../../share/share.module";
import { ReactiveFormsModule } from "@angular/forms";

@NgModule({
    imports: [
        CommonModule,
        SampleTypeRoutingModule,
        ShareModule,
        ReactiveFormsModule
    ],
    declarations: [ListComponent, FormComponent]
})
export class SampleTypeModule { }
