import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../share/share.module';
import { FeaturesRoutingModule } from './features-routing.module';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';

@NgModule({
    imports: [
        CommonModule,
        FeaturesRoutingModule,
        ReactiveFormsModule,
        ShareModule
    ],
    declarations: [ListComponent, FormComponent]
})
export class FeaturesModule { }
