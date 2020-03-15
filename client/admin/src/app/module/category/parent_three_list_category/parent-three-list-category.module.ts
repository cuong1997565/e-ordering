import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../../share/share.module';
import { ParentThreeListCategoryRoutingModule } from './parent-three-list-category-routing.module';

@NgModule({
    imports: [
        CommonModule,
        ReactiveFormsModule,
        ShareModule,
        ParentThreeListCategoryRoutingModule
    ],
    declarations: [ListComponent, FormComponent]
})
export class ParentThreeListCategoryModule { }
