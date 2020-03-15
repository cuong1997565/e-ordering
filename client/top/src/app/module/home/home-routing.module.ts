import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {ListComponent} from './list/list.component';
import {ProductComponent} from './product/product.component';
import { ConditionListProductResolver } from './condition-list-product.resolver';

const routes: Routes =
    [
        {
            path: '',
            component: ListComponent
        },
        {
            path: 'products',
            component: ProductComponent,
            resolve: {
                condition: ConditionListProductResolver
            }
        },
    ];

@NgModule({
    imports: [
        RouterModule.forChild(routes),

    ],
    exports: [RouterModule]
})
export class HomeRoutingModule {
}
