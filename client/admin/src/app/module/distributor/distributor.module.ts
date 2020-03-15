import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DistributorRoutingModule } from './distributor-routing.module';
import { ListComponent } from './list/list.component';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../share/share.module';
import { FormComponent } from './form/form.component';

@NgModule({
  imports: [
    CommonModule,
    DistributorRoutingModule,
    ReactiveFormsModule,
    ShareModule
  ],
  declarations: [ListComponent, FormComponent]
})
export class DistributorModule { }
