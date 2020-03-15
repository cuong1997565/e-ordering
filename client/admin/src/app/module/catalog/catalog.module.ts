import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListComponent } from './list/list.component';
import { CatalogRoutingModule } from './catalog-routing.module';
import { ShareModule } from '../../share/share.module';
import { ReactiveFormsModule } from '@angular/forms';

@NgModule({
  imports: [
    CommonModule,
    ShareModule,
    CatalogRoutingModule,
    ReactiveFormsModule
  ],
  declarations: [ListComponent]
})
export class CatalogModule { }
