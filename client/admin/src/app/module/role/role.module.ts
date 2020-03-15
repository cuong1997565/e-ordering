
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../share/share.module';
import { RoleRoutingModule } from './role-routing.module';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';


@NgModule({
    imports: [
        CommonModule,
        ReactiveFormsModule,
        ShareModule,
        RoleRoutingModule
    ],
    declarations: [ListComponent, FormComponent]
})
export class RoleModule { }
