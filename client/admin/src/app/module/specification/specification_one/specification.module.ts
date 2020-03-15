import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SpecificationRoutingModule } from './specification-routing.module';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../../share/share.module';

@NgModule({
  imports: [
    CommonModule,
    SpecificationRoutingModule,
    ReactiveFormsModule,
    ShareModule
  ],
  declarations: [ListComponent, FormComponent]
})
export class SpecificationModule { }