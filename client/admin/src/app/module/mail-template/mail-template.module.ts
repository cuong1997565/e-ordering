import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListComponent } from './list/list.component';
import { ShareModule } from "../../share/share.module";
import { ReactiveFormsModule } from "@angular/forms";
import { MailTemplateRoutingModule } from "./mail-template-routing.module";

@NgModule({
    imports: [
        CommonModule,
        ShareModule,
        ReactiveFormsModule,
        MailTemplateRoutingModule
    ],
    declarations: [ListComponent]
})
export class MailTemplateModule { }
