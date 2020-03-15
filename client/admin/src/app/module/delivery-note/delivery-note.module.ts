import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ReactiveFormsModule} from '@angular/forms';
import {ShareModule} from '../../share/share.module';
import {DeliveryNoteRoutingModule} from './delivery-note-routing.module';
import {ListComponent} from './list/list.component';
import {CreateComponent} from './create/create.component';
import {ReverseComponent} from './reverse/reverse.component';

@NgModule({
    imports: [
        CommonModule,
        ReactiveFormsModule,
        ShareModule,
        DeliveryNoteRoutingModule
    ],
    declarations: [ListComponent, CreateComponent, ReverseComponent]
})
export class DeliveryNoteModule {
}
