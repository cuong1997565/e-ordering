import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {MemberRoutingModule} from './member-routing.module';
import {ListComponent} from './list/list.component';
import {FormComponent} from './form/form.component';
import {ShareModule} from '../../share/share.module';
import {ReactiveFormsModule} from '@angular/forms';
import {ViewComponent} from './view/view.component';

@NgModule({
    imports: [
        CommonModule,
        MemberRoutingModule,
        ShareModule,
        ReactiveFormsModule
    ],
    declarations: [ListComponent, ViewComponent, FormComponent]
})
export class MemberModule {
}
