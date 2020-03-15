import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DashboardRoutingModule } from './dashboard-routing.module';
import { ListComponent } from './list/list.component';
import { ShareModule} from "../../share/share.module";

@NgModule({
    imports: [
        CommonModule,
        DashboardRoutingModule,
        ShareModule
    ],
    declarations: [ListComponent]
})
export class DashboardModule { }
