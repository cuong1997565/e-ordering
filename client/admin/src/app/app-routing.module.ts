import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {DefaultLayoutComponent} from './layout/default/default.component';
import {LoginLayoutComponent} from './layout/auth/auth.component';
import {AppResolver} from './share/app.resolver';
import {ProfileResolver} from './share/profile.resolver';
import {LangResolver} from './share/lang.resolver';
import {AuthGuard} from './services/guard/auth.guard';

const routes: Routes =
    [
        {
            path: '',
            component: DefaultLayoutComponent,
            children:
                [
                    {path: '', redirectTo: 'dashboard', pathMatch: 'full'},
                    {
                        path: 'dashboard',
                        loadChildren: './module/dashboard/dashboard.module#DashboardModule',
                        // canActivate : [AuthGuard]
                    },
                    {
                        path: 'user',
                        loadChildren: './module/user/user.module#UserModule',
                    },
                    {
                        path: 'sample',
                        loadChildren: './module/sample/sample/sample.module#SampleModule',
                    },
                    {
                        path: 'sample-type',
                        loadChildren: './module/sample/sample-type/sample-type.module#SampleTypeModule',
                    },
                    {
                        path: 'media',
                        loadChildren: './module/media/media.module#MediaModule',
                    },
                    {
                        path: 'mail-template',
                        loadChildren: './module/mail-template/mail-template.module#MailTemplateModule',
                    },
                    {
                        path: 'translation',
                        loadChildren: './module/translation/translation.module#TranslationModule',
                    },

                    {
                        path: 'distributor',
                        loadChildren: './module/distributor/distributor.module#DistributorModule',
                        // canActivate : [AuthGuard]
                    },

                    {
                        path: 'credit-account',
                        loadChildren: './module/credit-account/credit-account.module#CreditAccountModule',
                        // canActivate : [AuthGuard]
                    },

                    {
                        path: 'member',
                        loadChildren: './module/member/member.module#MemberModule',
                    },

                    {
                        path: 'factory',
                        loadChildren: './module/factory/factory.module#FactoryModule',
                    },

                    {
                        path: 'store',
                        loadChildren: './module/store/store.module#StoreModule',
                    },
                    {
                        path: 'province',
                        loadChildren: './module/area/province/province.module#ProvinceModule',
                    },
                    {
                        path: 'district',
                        loadChildren: './module/area/district/district.module#DistrictModule',
                    },
                    {
                        path: 'commune',
                        loadChildren: './module/area/commune/commune.module#CommuneModule',
                    },

                    {
                        path: 'brand',
                        loadChildren: './module/brand/brand.module#BrandModule',
                    },

                    {
                        path: 'catalog',
                        loadChildren: './module/catalog/catalog.module#CatalogModule',
                    },

                    {
                        path: 'specification',
                        loadChildren: './module/specification/specification_one/specification.module#SpecificationModule',
                    },

                    {
                        path: 'order',
                        loadChildren: './module/order/order.module#OrderModule'
                    },
                    {
                        path: 'sale-order',
                        loadChildren: './module/sale-order/sale-order.module#SaleOrderModule'
                    },
                    {
                        path: 'delivery-note',
                        loadChildren: './module/delivery-note/delivery-note.module#DeliveryNoteModule'
                    },
                    {
                        path: 'specification_two',
                        loadChildren: './module/specification/specification_two/specification-two.module#SpecificationTwoModule',
                    },

                    {
                        path: 'list_category',
                        loadChildren: './module/category/list_category/list-category.module#ListCategoryModule',
                    },
                    {
                        path: 'parent_list_category',
                        loadChildren: './module/category/parent_list_category/parent-list-category.module#ParentListCategoryModule',
                    },
                    {
                        path: 'parent_two_list_category',
                        // tslint:disable-next-line:max-line-length
                        loadChildren: './module/category/parent_two_list_category/parent-two-list-category.module#ParentTwoListCategoryModule',
                    },
                    {
                        path: 'parent_three_list_category',
                        // tslint:disable-next-line:max-line-length
                        loadChildren: './module/category/parent_three_list_category/parent-three-list-category.module#ParentThreeListCategoryModule',
                    },
                    {
                        path: 'product',
                        loadChildren: './module/product/product.module#ProductModule'
                    },
                    {
                        path: 'uom',
                        loadChildren: './module/uom/uom.module#UomModule'
                    },
                    {
                        path: 'uom-multiple',
                        loadChildren: './module/uom-multiples/uom-multiples.module#UomMultiplesModule'
                    },
                    {
                        path: 'grade-group',
                        loadChildren: './module/grade-groups/grade-groups.module#GradeGroupsModule'
                    },
                    {
                        path: 'grade',
                        loadChildren: './module/grade/grade.module#GradeModule'
                    },
                    {
                        path: 'product-type',
                        loadChildren: './module/product-type/product-type.module#ProductTypeModule'
                    },
                    {
                        path: 'features',
                        loadChildren: './module/features/features.module#FeaturesModule'
                    },
                    {
                        path: 'feature-items',
                        loadChildren: './module/feature-items/feature-items.module#FeatureItemsModule'
                    },
                    {
                        path: 'attributes',
                        loadChildren: './module/attributes/attributes.module#AttributesModule'
                    },
                    {
                        path: 'attribute-list-of-value',
                        loadChildren: './module/attribute-list-of-value/attribute-list-of-value.module#AttributeListOfValueModule'
                    },
                    {
                        path: 'roles',
                        loadChildren: './module/role/role.module#RoleModule'
                    },
                    {
                        path: 'price',
                        loadChildren: './module/price-list/price-list.module#PriceListModule'
                    },
                    {
                        path: 'credit-transaction',
                        loadChildren: './module/credit-transaction/credit-transaction.module#CreditTransactionModule'
                    },
                    {
                        path: 'discount-type',
                        loadChildren: './module/discount-type/discount-type.module#DiscountTypeModule'
                    }
                ],
            resolve: {lang: LangResolver, app: AppResolver, profile: ProfileResolver}
        },
        {
            path: 'auth',
            component: LoginLayoutComponent,
            children:
                [
                    {path: '', loadChildren: './module/auth/auth.module#AuthModule'}
                ],
            resolve: {lang: LangResolver, app: AppResolver}
        },

    ];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})
export class AppRoutingModule {
}
