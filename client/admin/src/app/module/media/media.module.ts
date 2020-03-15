import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MediaRoutingModule } from './media-routing.module';
import { ListComponent } from './list/list.component';
import { ShareModule} from "../../share/share.module";

@NgModule({
    imports: [
        CommonModule,
        MediaRoutingModule,
        ShareModule
    ],
    declarations: [ListComponent]
})
export class MediaModule { }
