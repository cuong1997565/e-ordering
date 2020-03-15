import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CatalogRoutingModule } from './catalog-routing.module';
import { ListComponent } from './list/list.component';
import { ShareModule } from '../../share/share.module';
import { ReactiveFormsModule } from '@angular/forms';

@NgModule({
  imports: [
    CommonModule,
    CatalogRoutingModule,
    ShareModule,
    ReactiveFormsModule  
  ],
  declarations: [ListComponent]
})
export class CatalogModule { }
