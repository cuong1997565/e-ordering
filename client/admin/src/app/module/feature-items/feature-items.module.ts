import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../share/share.module';
import { FeatureItemsRoutingModule } from './feature-items-routing.module';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { ListItemComponent } from './list-item/list-item.component';
import { FormItemComponent } from './form-item/form-item.component';

@NgModule({
    imports: [
        CommonModule,
        FeatureItemsRoutingModule,
        ReactiveFormsModule,
        ShareModule
    ],
    declarations: [ListComponent, FormComponent, ListItemComponent, FormItemComponent]
})
export class FeatureItemsModule { }
