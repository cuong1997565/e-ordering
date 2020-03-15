import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { ShareModule } from "../../share/share.module";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { TranslationRoutingModule } from "./translation-routing.module";
import { GenComponent } from "./gen/list.component";
import { DevComponent } from "./dev/dev.component";

@NgModule({
    imports: [
        CommonModule,
        TranslationRoutingModule,
        ShareModule,
        ReactiveFormsModule,
        FormsModule,
    ],
    declarations: [ListComponent, FormComponent, GenComponent, DevComponent]
})
export class TranslationModule { }
