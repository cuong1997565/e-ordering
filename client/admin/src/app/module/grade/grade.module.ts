import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import {ListItemComponent} from './list-item/list-item.component';
import { FormItemComponent } from './form-item/form-item.component';
import { GradeRoutingModule } from './grade-routing.module';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../share/share.module';

@NgModule({
    imports: [
        CommonModule,
        GradeRoutingModule,
        ReactiveFormsModule,
        ShareModule
    ],
    declarations: [ListComponent, FormComponent, FormItemComponent, ListItemComponent]
})
export class GradeModule { }
