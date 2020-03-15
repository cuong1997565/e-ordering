import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { ReactiveFormsModule } from '@angular/forms';
import { ListCategoryRoutingModule } from './list-category-routing.module';
import { ShareModule } from '../../../share/share.module';

@NgModule({
  imports: [
    CommonModule,
    ShareModule,
    ListCategoryRoutingModule,
    ReactiveFormsModule,
  ],
  declarations: [ListComponent, FormComponent]
})
export class ListCategoryModule { }
