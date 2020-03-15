import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../share/share.module';
import { FactoryRoutingModule } from './factory-routing.module';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';

@NgModule({
  imports: [
    CommonModule,
    FactoryRoutingModule,
    ReactiveFormsModule,
    ShareModule
  ],
  declarations: [ListComponent, FormComponent]
})
export class FactoryModule { }
