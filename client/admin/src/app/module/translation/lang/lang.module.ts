import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { ShareModule } from "../../../share/share.module";
import { ReactiveFormsModule } from "@angular/forms";
import { LangRoutingModule } from "./lang-routing.module";

@NgModule({
    imports: [
        CommonModule,
        LangRoutingModule,
        ShareModule,
        ReactiveFormsModule
    ],
    declarations: [ListComponent, FormComponent]
})
export class LangModule { }
