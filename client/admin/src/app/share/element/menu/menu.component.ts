import {ChangeDetectorRef, Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import * as $ from 'jquery';
import {AppService} from '../../app.service';
import {FormArray} from "@angular/forms";

@Component({
    selector: 'ele-menu',
    templateUrl: './menu.component.html'
})
export class MenuComponent implements OnInit {

    public menus;
    private activeMenu;

    constructor(private router: Router, public app: AppService, public detector: ChangeDetectorRef) {
    }

    ngOnInit() {
        this.generateMenus();
        this.activeMenu = window.location.pathname;
    }

    gotoUrl(url) {
        if (!url || url === '#') {
            return false;
        }
        this.activeMenu = url;
        this.checkActiveMenu(this.activeMenu);
        this.router.navigateByUrl(url);
    }

    ngDoCheck() {
        if (this.activeMenu !== window.location.pathname) {
            this.activeMenu = window.location.pathname;
            this.checkActiveMenu(this.activeMenu);
        }
    }

    checkActiveMenu(action) {
        let parentActive = $('a[action="' + action + '"]').parents('.menuList');
        if (parentActive.hasClass('open')) {
            return false;
        }
        $('.menuList').removeClass('open');
        $('.menuList ul').hide();
        parentActive.addClass('open').find('ul').slideDown();
    }

    generateMenus() {
        /*const role = this.app.curUser.role;
        if (this.app.curUser.group === this.app.constant.GROUP_ADMIN) {
            this.menus = [
                {
                    text: 'Dashboard',
                    icon: 'fa-home',
                    url: '/dashboard'
                }
            ];

            if (role) {
                const permissions = role.permissions;
                // distributor module
                if (permissions['list_distributor'] || permissions['create_distributor']) {
                    const object = {
                        text: 'Distributor',
                        icon: 'fa-industry',
                        url: '#',
                        children: []
                    };
                    if (permissions['list_distributor']) {
                        object.children.push({
                            icon: 'fa-list',
                            text: 'List distributors',
                            url: '/distributor/list'
                        });
                    }
                    if (permissions['create_distributor']) {
                        object.children.push({
                            icon: 'fa-pencil-square-o',
                            text: 'New distributor',
                            url: '/distributor/form'
                        });
                    }
                    this.menus.push(object);
                }
                if (permissions['list_orders']) {
                    const object = {
                        icon: 'fa-industry',
                        text: 'Order',
                        url: '#',
                        children: [
                            {
                                icon: 'fa-list',
                                text: 'List order',
                                url: '/order/list'
                            },
                        ]
                    };
                    this.menus.push(object);
                }
                if (permissions['list_all_so_distributor']) {
                    const object = {
                        icon: 'fa-industry',
                        text: 'Sale Order',
                        url: '#',
                        children: [
                            {
                                icon: 'fa-list',
                                text: 'List sale order',
                                url: '/sale-order/list'
                            },
                        ]
                    };
                    this.menus.push(object);
                }
                if (permissions['list_all_dn_distributor']) {
                    const object = {
                        icon: 'fa-industry',
                        text: 'Delivery Note',
                        url: '#',
                        children: [
                            {
                                icon: 'fa-list',
                                text: 'List delivery note',
                                url: '/delivery-note/list'
                            },
                        ]
                    };
                    this.menus.push(object);
                }
                if (permissions['list_stores']) {
                    const object = {
                        icon: 'fa-industry',
                        text: 'Store',
                        url: '#',
                        children: [
                            {
                                icon: 'fa-list',
                                text: 'List store',
                                url: '/store/list'
                            },
                        ]
                    };
                    this.menus.push(object);
                }
                if (permissions['list_products']) {
                    const object = {
                        icon: 'fa-industry',
                        text: 'Product',
                        url: '#',
                        children: [
                            {
                                icon: 'fa-list',
                                text: 'List product',
                                url: '/product/list'
                            },
                        ]
                    };
                    this.menus.push(object);
                }
                if (permissions['list_credit_accounts']) {
                    const object = {
                        icon: 'fa-industry',
                        text: 'Credit Account',
                        url: '#',
                        children: [
                            {
                                icon: 'fa-list',
                                text: 'List',
                                url: '/credit-account/list'
                            },
                        ]
                    };
                    this.menus.push(object);
                }
                if (permissions['list_factories']) {
                    const object = {
                        icon: 'fa-industry',
                        text: 'Factory',
                        url: '#',
                        children: [
                            {
                                icon: 'fa-list',
                                text: 'List factorys',
                                url: '/factory/list'
                            },
                        ]
                    };
                    this.menus.push(object);
                }
                if (permissions['list_areas']) {
                    const object = {
                        icon: 'fa-gears',
                        text: 'Area',
                        url: '#',
                        children: [
                            {
                                icon: 'fa-user-md',
                                text: 'Provinces',
                                url: '/province'
                            },
                        ]
                    };
                    this.menus.push(object);
                }
                if (permissions['list_categories']) {
                    const object = {
                        icon: 'fa-gears',
                        text: 'Category',
                        url: '#',
                        children: [
                            {
                                icon: 'fa-user-md',
                                text: 'List categorys',
                                url: '/list_category'
                            },
                        ]
                    };
                    this.menus.push(object);
                }
                if (permissions['list_price']) {
                    const object = {
                        icon: 'fa-industry',
                        text: 'Price',
                        url: '#',
                        children: [
                            {
                                icon: 'fa-list',
                                text: 'List',
                                url: '/price/list'
                            },
                        ]
                    };
                    this.menus.push(object);
                }
                if (permissions['list_discount_type']) {
                    const object = {
                        icon: 'fa-industry',
                        text: 'Discount Type',
                        url: '#',
                        children: [
                            {
                                icon: 'fa-list',
                                text: 'List',
                                url: '/discount-type/list'
                            },
                        ]
                    };
                    this.menus.push(object);
                }
                const masterdatas = [
                    {
                        icon: 'fa-list',
                        text: 'List role',
                        url: '/roles/list'
                    }
                ];
                // Admin module
                if (permissions['list_admins']) {
                    const object = {
                        icon: 'fa-user-md',
                        text: 'Admin',
                        url: '/user/list'
                    };
                    masterdatas.push(object);
                }
                // Member module
                if (permissions['list_members']) {
                    const object = {
                        text: 'Member',
                        icon: 'fa-user',
                        url: '/member/list'
                    };
                    masterdatas.push(object);
                }
                if (permissions['list_uoms']) {
                    const object = {
                        icon: 'fa-list',
                        text: 'List Uom',
                        url: '/uom/list'
                    };
                    masterdatas.push(object);
                }
                // if (permissions['list_uom_multiples']) {
                //     const object = {
                //         icon: 'fa-list',
                //         text: 'List Uom Multiples',
                //         url: '/uom-multiple/list'
                //     };
                //     masterdatas.push(object);
                // }
                if (permissions['list_product_types']) {
                    const object = {
                        icon: 'fa-list',
                        text: 'List Product Types',
                        url: '/product-type/list'
                    };
                    masterdatas.push(object);
                }
                if (permissions['list_grades']) {
                    const object = {
                        icon: 'fa-list',
                        text: 'List Grade',
                        url: '/grade/list'
                    };
                    masterdatas.push(object);
                }
                if (permissions['list_grade_groups']) {
                    const object = {
                        icon: 'fa-list',
                        text: 'List Grade Group',
                        url: '/grade-group/list'
                    };
                    masterdatas.push(object);
                }
                if (permissions['list_features']) {
                    const object = {
                        icon: 'fa-list',
                        text: 'List Feature',
                        url: '/features/list'
                    };
                    masterdatas.push(object);
                }
                if (permissions['list_feature_items']) {
                    const object = {
                        icon: 'fa-list',
                        text: 'List Feature Item',
                        url: '/feature-items/list'
                    };
                    masterdatas.push(object);
                }
                if (permissions['list_attributes']) {
                    const object = {
                        icon: 'fa-list',
                        text: 'List Attribute',
                        url: '/attributes/list'
                    };
                    masterdatas.push(object);
                }
                if (permissions['list_attribute_list_of_values']) {
                    const object = {
                        icon: 'fa-list',
                        text: 'List Attribute Of Values',
                        url: '/attribute-list-of-value/list'
                    };
                    masterdatas.push(object);
                }
                const masterdatasMenu = {
                    text: 'Master data',
                    icon: 'fa-gears',
                    url: '#',
                    children: masterdatas
                };
                this.menus.push(masterdatasMenu);
            }
            this.menus.push(
                {
                    text: 'Credit Transaction',
                    icon: 'fa-industry',
                    url: '#',
                    children: [
                        {
                            icon: 'fa-list',
                            text: 'List',
                            url: '/credit-transaction/list'
                        },
                    ]
                },
            );
        }
        if (this.app.curUser.group === this.app.constant.GROUP_SALE_FACTORY) {
            this.menus = [
                {
                    text: 'Dashboard',
                    icon: 'fa-home',
                    url: '/dashboard'
                },
                {
                    text: 'Product',
                    icon: 'fa-industry',
                    url: '#',
                    children: [
                        {
                            icon: 'fa-list',
                            text: 'List product',
                            url: '/product/list'
                        },
                    ]
                },
                {
                    text: 'Order',
                    icon: 'fa-industry',
                    url: '#',
                    children: [
                        {
                            icon: 'fa-list',
                            text: 'List order',
                            url: '/order/list'
                        },
                    ]
                }
            ];
        }*/

        // duynb : optimize menu & permission

        if (this.app.curUser.group === this.app.constant.GROUP_ADMIN) {
            this.menus = [
                {
                    text: 'Dashboard',
                    icon: 'fa-home',
                    url: '/dashboard',
                    permission: []
                },
                {
                    text: 'Order',
                    icon: 'fa-copy',
                    url: '#',
                    permission: [
                        this.app.constant.PERMISSION_LIST_ORDERS,
                        this.app.constant.PERMISSION_LIST_ALL_SO_ORDER,
                        this.app.constant.PERMISSION_LIST_ALL_DN_ORDER
                    ],
                    children: [
                        {
                            icon: 'fa-copy',
                            text: 'Order',
                            url: '/order/list',
                            permission: [this.app.constant.PERMISSION_LIST_ORDERS]
                        },
                        {
                            icon: 'fa-file-o',
                            text: 'Sale order',
                            url: '/sale-order/list',
                            permission: [this.app.constant.PERMISSION_LIST_ALL_SO_ORDER]
                        },
                        {
                            icon: 'fa-truck',
                            text: 'Delivery note',
                            url: '/delivery-note/list',
                            permission: [this.app.constant.PERMISSION_LIST_ALL_DN_ORDER]
                        }
                    ]
                },
                {
                    text: 'Product',
                    icon: 'fa-cube',
                    url: '#',
                    permission: [
                        this.app.constant.PERMISSION_LIST_PRODUCTS,
                        this.app.constant.PERMISSION_LIST_PRICES,
                        this.app.constant.PERMISSION_LIST_GRADES,
                        this.app.constant.PERMISSION_LIST_GRADE_GROUPS,
                        this.app.constant.PERMISSION_LIST_CATEGORIES,
                        this.app.constant.PERMISSION_LIST_FEATURES,
                        this.app.constant.PERMISSION_LIST_FEATURE_ITEMS,
                        this.app.constant.PERMISSION_LIST_ATTRIBUTES,
                        this.app.constant.PERMISSION_LIST_ATTRIBUTE_LIST_OF_VALUES,
                        this.app.constant.PERMISSION_LIST_PRODUCT_TYPES,
                        this.app.constant.PERMISSION_LIST_UOM_MULTIPLES,
                        this.app.constant.PERMISSION_LIST_DISCOUNTS_TYPE,
                    ],
                    children: [
                        {
                            icon: 'fa-cube',
                            text: 'Product',
                            url: '/product/list',
                            permission: [this.app.constant.PERMISSION_LIST_PRODUCTS]
                        },
                        {
                            icon: 'fa-usd',
                            text: 'Price',
                            url: '/price/list',
                            permission: [this.app.constant.PERMISSION_LIST_PRICES]
                        },
                        {
                            icon: 'fa-columns',
                            text: 'Grade',
                            url: '/grade/list',
                            permission: [this.app.constant.PERMISSION_LIST_GRADES]
                        },
                        {
                            icon: 'fa-square-o',
                            text: 'Grade group',
                            url: '/grade-group/list',
                            permission: [this.app.constant.PERMISSION_LIST_GRADE_GROUPS]
                        },
                        {
                            icon: 'fa-cubes',
                            text: 'Category',
                            url: '/list_category',
                            permission: [this.app.constant.PERMISSION_LIST_CATEGORIES]
                        },
                        {
                            icon: 'fa-cog',
                            text: 'Feature',
                            url: '/features/list',
                            permission: [this.app.constant.PERMISSION_LIST_FEATURES]
                        },
                        {
                            icon: 'fa-gears',
                            text: 'Feature item',
                            url: '/feature-items/list',
                            permission: [this.app.constant.PERMISSION_LIST_FEATURE_ITEMS]
                        },
                        {
                            icon: 'fa-dashboard',
                            text: 'Attribute',
                            url: '/attributes/list',
                            permission: [this.app.constant.PERMISSION_LIST_ATTRIBUTES]
                        },
                        {
                            icon: 'fa-list-ul',
                            text: 'Attribute of values',
                            url: '/attribute-list-of-value/list',
                            permission: [this.app.constant.PERMISSION_LIST_ATTRIBUTE_LIST_OF_VALUES]
                        },
                        {
                            icon: 'fa-cubes',
                            text: 'Product types',
                            url: '/product-type/list',
                            permission: [this.app.constant.PERMISSION_LIST_PRODUCT_TYPES]
                        },
                        {
                            icon: 'fa-list',
                            text: 'Uom',
                            url: '/uom/list',
                            permission: [this.app.constant.PERMISSION_LIST_UOM_MULTIPLES]
                        },
                        {
                            icon: 'fa-sort-amount-desc',
                            text: 'Discount',
                            url: '/discount-type/list',
                            permission: [this.app.constant.PERMISSION_LIST_DISCOUNTS_TYPE]
                        }
                    ]
                },
                {
                    text: 'Distributor',
                    icon: 'fa-sitemap',
                    url: '#',
                    permission: [
                        this.app.constant.PERMISSION_LIST_DISTRIBUTORS,
                    ],
                    children: [
                        {
                            icon: 'fa-sitemap',
                            text: 'Distributor',
                            url: '/distributor/list',
                            permission: [this.app.constant.PERMISSION_LIST_DISTRIBUTORS]
                        },
                        {
                            icon: 'fa-exchange',
                            text: 'Transaction',
                            url: '/credit-transaction/list',
                            permission: []
                        }

                    ]
                },
                {
                    text: 'Account',
                    icon: 'fa-user-md',
                    url: '#',
                    permission: [
                        this.app.constant.PERMISSION_LIST_ADMINS,
                        this.app.constant.PERMISSION_LIST_ROLES,
                        this.app.constant.PERMISSION_LIST_MEMBERS,
                    ],
                    children: [
                        {
                            icon: 'fa-user-md',
                            text: 'User',
                            url: '/user/list',
                            permission: [this.app.constant.PERMISSION_LIST_ADMINS]
                        },
                        {
                            icon: 'fa-group',
                            text: 'Role',
                            url: '/roles/list',
                            permission: [this.app.constant.PERMISSION_LIST_ROLES]
                        },
                        {
                            text: 'Member',
                            icon: 'fa-user',
                            url: '/member/list',
                            permission: [this.app.constant.PERMISSION_LIST_MEMBERS]
                        }
                    ]
                },
                {
                    text: 'Master data',
                    icon: 'fa-list-alt',
                    url: '#',
                    permission: [
                        this.app.constant.PERMISSION_LIST_STORES,
                        this.app.constant.PERMISSION_LIST_FACTORIES,
                        this.app.constant.PERMISSION_LIST_AREAS,
                    ],
                    children: [
                        {
                            icon: 'fa-th',
                            text: 'Store',
                            url: '/store/list',
                            permission: [this.app.constant.PERMISSION_LIST_STORES]
                        },
                        {
                            icon: 'fa-industry',
                            text: 'Factory',
                            url: '/factory/list',
                            permission: [this.app.constant.PERMISSION_LIST_FACTORIES]
                        },
                        {
                            icon: 'fa-map',
                            text: 'Area',
                            url: '/province',
                            permission: [this.app.constant.PERMISSION_LIST_AREAS]
                        },
                    ]
                }
            ];
        } else if (this.app.curUser.group === this.app.constant.GROUP_SALE_FACTORY) {

            this.menus = [
                {
                    text: 'Dashboard',
                    icon: 'fa-home',
                    url: '/dashboard',
                    permission: []
                },
                {
                    text: 'Order',
                    icon: 'fa-copy',
                    url: '#',
                    permission: [],
                    children: [
                        {
                            icon: 'fa-copy',
                            text: 'Order',
                            url: '/order/list',
                            permission: []
                        }
                    ]
                },
                {
                    text: 'Product',
                    icon: 'fa-cube',
                    url: '#',
                    permission: [],
                    children: [
                        {
                            icon: 'fa-cube',
                            text: 'Product',
                            url: '/product/list',
                            permission: []
                        },
                    ]
                }
            ];
        }
    }

    // Check show menu base on permission
    showMenu(menuItem) {

        if (menuItem.permission) {

            if (menuItem.permission.length === 0) {
                return true; // Not define -> show for all
            }

            const userPermission = this.app.curUser.role.permissions;
            for (const permission in userPermission) {

                if (menuItem.permission.includes(permission)) {
                    return true;
                }
            }
        }

        return false;
    }
}
