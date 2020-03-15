import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { StoreRoutingModule } from './store-routing.module';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../share/share.module';

@NgModule({
  imports: [
    CommonModule,
    StoreRoutingModule,
    ReactiveFormsModule,
    ShareModule
  ],
  declarations: [ListComponent, FormComponent]
})
export class StoreModule { }
