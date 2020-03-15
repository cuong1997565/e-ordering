import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CommuneRoutingModule } from './commune-routing.module';
import { ListComponent } from './list/list.component';
import {FormComponent} from "./form/form.component";
import { ShareModule} from "../../../share/share.module";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
@NgModule({
    imports: [
        CommonModule,
        CommuneRoutingModule,
        ShareModule,
        ReactiveFormsModule
    ],
    declarations: [ListComponent, FormComponent]
})
export class CommuneModule { }
