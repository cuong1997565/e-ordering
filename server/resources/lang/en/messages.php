<?php

return [

    //customer
    'store.customer.save.existing.app_error' => 'Must call update for exisiting customer',
    'store.customer.save.app_error' => 'Unable to save the customer',
    'store.customer.update.app_error' => 'Unable to update the customer',
    'store.customer.update.updating.app_error' => 'We encountered an error updating the customer',
    'store.customer.update.find.app_error' => 'Unable to find the existing customer to update',
    'store.customer.update.finding.app_error' => 'We encountered an error finding the customer',
    'store.customer.get.find.app_error' => 'Unable to find the existing customer',
    'store.customer.get.finding.app_error' => 'We encountered an error finding the customer',
    'store.customer.get_by_name.app_error' => 'Unable to find the existing customer with name%: :Name',
    'store.customer.search_by_name.app_error' => 'Unable to find the existing customer with name: :Name %',
    'store.customer.get_by_name.first.app_error' => 'Unable to find the existing customer with name: :Name',
    'store.customer.get_by_store_id.app_error' => 'Unable to find the existing customer with StoreId: :StoreId',
    'store.customer.get_by_store_id.first.app_error' => 'Unable to find the existing customer with StoreId: :StoreId',
    'model.customer.is_valid.id.app_error' => 'Invalid Id',
    'model.customer.is_valid.name.app_error' => 'Invalid name',
    'model.customer.is_valid.store_id.app_error' => 'Invalid store_id',
    'api.invalid_url_param.app_error' => "The :Name field is required",
    'api.in_value_param.app_error' => "The :Name field is invalid",
    'api.integer_param.app_error' => "The :Name field is integer",
    'api.format_email.app_error' => "The email format is invalid.",
    'api.no_space.app_error' => "Field must not have spaces.",
    'api.min.app_error' => "The :Name must be at least 8 characters.",
    'api.min_name.app_error' => "The :Name must be at least 3 characters.",
    'api.max.app_error' => "The :Name may not be greater than 255 characters.",
    'api.phone_max.app_error' => "The :Name may not be greater than 11 characters.",
    'api.is_ascii.app_error' => "The :Name field is invalid",
    'api.same.app_error' => "The password confirmation does not match.",
    'store.customer.time.email' => 'Email update time is invalid',
    //end customer

    //area
    'store.area.save.existing.app_error' => 'Must call update for exisiting area',
    'store.area.save.app_error' => 'Unable to save the area',
    'model.area.is_valid.id.app_error' => 'Invalid Id',
    'store.area.update.find.app_error' => 'Unable to find the existing area to update',
    'store.area.update.finding.app_error' => 'We encountered an error finding the area',
    'store.area.update.app_error' => 'Unable to update the area',
    'store.area.update.updating.app_error' => 'We encountered an error updating the area',
    'model.area.is_valid.name.app_error' => 'Invalid Name',
    'store.area.get_by_name.app_error' => 'Unable to find the existing area with name%: :Name',
    'store.area.get_by_name.first.app_error' => 'Unable to find the existing area with name: :Name',
    //end area

    //stores
    'store.stores.save.existing.app_error' => 'Must call update for exisiting stores',
    'model.store.is_valid.area_id.app_error' => 'Invalid Area Id',
    'model.store.is_valid.distributor_id.app_error' => 'Invalid Distributor Id',
    'store.stores.save.app_error' => 'Unable to save the store',
    'store.stores.factory.find.app_error' => 'Unable to find the existing factory to create and update',
    'store.stores.update.find.app_error' => 'Unable to find the existing store to update',
    'store.stores.update.app_error' => 'Unable to update the store',
    'store.stores.update.updating.app_error' => 'We encountered an error updating the store',
    'api.regex.code.stores.app_error' => 'The :Name is invalid',
    //end stores

    //distributor
    'store.distributor.save.existing.app_error' => 'Must call update for exisiting distributor',
    'store.distributor.save.app_error' => 'Unable to save the distributor',
    'model.distributor.is_valid.id.app_error' => 'Invalid Id',
    'store.distributor.update.find.app_error' => 'Unable to find the existing distributor to update',
    'store.distributor.update.finding.app_error' => 'We encountered an error finding the distributor',
    'store.distributor.update.app_error' => 'Unable to update the distributor',
    'store.distributor.update.updating.app_error' => 'We encountered an error updating the distributor',
    'model.distributor.is_valid.name.app_error' => 'Invalid Name',
    'store.distributor.get_by_name.app_error' => 'Unable to find the existing distributor with name%: :Name',
    'store.distributor.get_by_name.first.app_error' => 'Unable to find the existing distributor with name: :Name',
    'api.amount.distributor.app_error' => 'Min :  :Min , Max : :Max',

    //end distributor

    //product
    'store.product.save.existing.app_error' => 'Must call update for exisiting product',
    'store.product.save.app_error' => 'Unable to save the product',
    'model.product.is_valid.id.app_error' => 'Invalid Id',
    'api.max.product.app_error' => 'The :Name may not be greater than :Value characters.',
    'api.max.price.product.app_error' => 'Please enter a :Name smaller than 99999999.99',
    'store.product.update.updating.app_error' => 'We encountered an error updating the product',
    'store.stores.product.find.app_error' => 'Unable to find the existing product to create and update',
    'api.regex.code.product.app_error' => 'The :Name is invalid',
    'api.date.product.app_error' => 'The :Name can type date',
    'store.store.product.type.update.find.app_error' => 'Unable to find the existing product type  to update',
    'store.store.uoms.update.find.app_error' => 'Unable to find the existing uom  to update',
    'store.store.grade.group.update.find.app_error' => 'Unable to find the existing grade group to update',
    'store.store.feature.item.update.find.app_error' => 'Unable to find the existing feature item to update',
    'store.store.price.list.update.find.app_error' => 'Unable to find the existing price list to update',
    'store.store.grade.update.find.app_error' => 'Unable to find the existing grade to update',
    'store.store.stores.update.find.app_error' => 'Unable to find the existing stores to update',
    //end product

    //factory
    'store.factory.save.existing.app_error' => 'Must call update for exisiting factories',
    'store.factory.save.app_error' => 'Unable to save the factories',
    'model.factory.is_valid.id.app_error' => 'Invalid Id',
    'store.factory.update.find.app_error' => 'Unable to find the existing factory to update',
    'store.factory.update.app_error' => 'Unable to update the factory',
    'store.factory.update.updating.app_error' => 'We encountered an error updating the factory',
    'store.factory.get.finding.app_error' => 'We encountered an error finding the factory',
    'api.exist.app_error' => "The :Name has already been taken",
    'store.stores.factory.find.app_error' => 'Unable to find the existing factory to create and update',
    'api.regex.code.factory.app_error' => 'The :Name is invalid',
    //end factory

    //specification
    'store.specification.update.finding.app_error' => 'We encountered an error finding the specification',
    //end specification

    //image
    'api.image.image.app_error' => 'The upload :Name is not ininteger the correct format',
    'api.image.mimes.app_error' => 'Upload :Name must be one of these formats: :Value',
    //end image

    //integer
    'api.integer.app_error' => ':Name must be a number',
    'api.integer.price.app_error' => 'The :Name field is integer',
    'api.min.price.app_error' => 'The :Name field is integer',
    'api.min.credit_limit.app_error' => 'The :Name field is integer',
    'api.between.app_error' => 'The :Name field is integer',
    //end integer

    //phone
    'api.min.phone.app_error' => ":Name minimum number of 10 numbers",
    'api.number.phone.app_error' => ":Name is not numeric",
    //end phone

    //branch
        'store.stores.branch.find.app_error' => 'Unable to find the existing branch to create and update',
    //end branch

    //category
        'store.stores.category.find.app_error' => 'Unable to find the existing category to create and update',
    //end category

    //login client
    'model.customer.do_login.not_active' => "Account is blocked",
    'model.customer.do_login.wrong_password' => "Invalid username or password",
    'store.session.save.existing.app_error' => "Must call save for exisiting login",
    'store.session.save.app_error' => 'Unable to save the login',
    'model.customer.is_valid.email.app_error' => "",
    'model.session.is_valid.token_or_id.app_error' => 'Invalid :Name',
    'store.session.get.app_error' => 'We encountered an error finding the session',
    'api.middleware.session_expired.app_error' => 'Invalid or expired session, please login again.',
    'api.invalid_url_param.validate_error' => 'Invalid :Name',
    //end login client

    //order
    'store.order.save.existing.app_error' => 'Must call update for exisiting order',
    'store.stores.customer.find.app_error' => 'Unable to find the existing customer to create and update',
    'store.order.save.app_error' => 'Unable to save the order',
    'store.products.update.finding.app_error' => 'We encountered an error finding the products',
    'model.order.is_valid.id.app_error' => 'Invalid Id order',
    'store.stores.order.find.app_error' => 'Unable to find the existing orders to create and update',
    //end order

    //uom
    'api.min.uoms.app_error' => 'The :Name can not type',
    'api.numberic.uoms.app_error' => 'The :Name can type integer',
    'store.uom.save.existing.app_error' => 'Must call update for exisiting uoms',
    'store.uom.save.app_error' => 'Unable to save the uoms',
    'model.uoms.is_valid.id.app_error' => 'Invalid Id',
    'store.uoms.update.find.app_error' => 'Unable to find the existing uom to update',
    'store.uoms.update.app_error' => 'Unable to update the uom',
    'store.uoms.update.updating.app_error' => 'We encountered an error updating the uom',
    'store.stores.based.uom.id.find.app_error' => 'Unable to find the existing based uom id about table uoms',

    //end uom

    //uom multiple
    'api.min.uom.multiple.app_error' => 'The :Name can not type',
    'api.numberic.uom.multiple.app_error' => 'The :Name can type integer',
    'store.uom.multiple.save.existing.app_error' => 'Must call update for exisiting uom multiple',
    'store.uom.multiple.save.app_error' => 'Unable to save the uom multiple',
    'model.uom.multiple.is_valid.id.app_error' => 'Invalid Id',
    'store.uom.multiple.update.find.app_error' => 'Unable to find the existing uom to update multiple',
    'store.uom.multiple.update.app_error' => 'Unable to update the uom multiple',
    'store.uom.multiple.update.updating.app_error' => 'We encountered an error updating the uom multiple',
    'store.stores.uom.find.app_error' => 'Unable to find the existing uoms to create and update',
    //end uom multiple


    //grade group
    'store.grade.groups.save.existing.app_error' => 'Must call update for exisiting grade groups',
    'store.grade.groups.save.app_error' => 'Unable to save the grade groups',
    'model.grade.groups.is_valid.id.app_error' => 'Invalid Id',
    'store.grade.groups.update.find.app_error' => 'Unable to find the existing grade groups to update',
    'store.grade.groups.update.app_error' => 'Unable to update the grade groups',
    'store.grade.groups.update.updating.app_error' => 'We encountered an error updating the grade groups',
    //end grade group

    //grade
    'store.grade.save.existing.app_error' => 'Must call update for exisiting grades',
    'store.grade.save.app_error' => 'Unable to save the grades',
    'model.grade.is_valid.id.app_error' => 'Invalid Id',
    'store.grade.update.find.app_error' => 'Unable to find the existing grades  to update',
    'store.grade.update.app_error' => 'Unable to update the grades',
    'store.grade.update.updating.app_error' => 'We encountered an error updating the grades',
    'store.stores.grade.groups.find.app_error' => 'Unable to find the existing grade groups to create and update',
    //end grade

    //product type
    'store.product.type.save.existing.app_error' => 'Must call update for exisiting product type',
    'store.product.type.save.app_error' => 'Unable to save the product type',
    'model.product.type.is_valid.id.app_error' => 'Invalid Id',
    'store.product.type.update.find.app_error' => 'Unable to find the existing product type  to update',
    'store.product.type.update.app_error' => 'Unable to update the product type',
    'store.product.type.update.updating.app_error' => 'We encountered an error updating the product type',
    //end product type

    //features
    'store.features.save.existing.app_error' => 'Must call update for exisiting features',
    'store.features.save.app_error' => 'Unable to save the features',
    'model.features.is_valid.id.app_error' => 'Invalid Id',
    'store.features.update.find.app_error' => 'Unable to find the existing features to update',
    'store.features.update.app_error' => 'Unable to update the features',
    'store.features.update.updating.app_error' => 'We encountered an error updating the features',
    //end features

    //feature items
    'store.feature.items.save.existing.app_error' => 'Must call update for exisiting feature items',
    'store.feature.items.save.app_error' => 'Unable to save the feature items',
    'model.feature.items.is_valid.id.app_error' => 'Invalid Id',
    'store.feature.items.update.find.app_error' => 'Unable to find the existing feature items to update',
    'store.feature.items.update.app_error' => 'Unable to update the feature items',
    'store.feature.items.update.updating.app_error' => 'We encountered an error updating the feature items',
    'store.stores.features.find.app_error' => 'Unable to find the existing feature to create and update',
    //end feature items
    //roles
    'api.regex.code.roles.app_error' => 'The :Name is invalid',

    //end roles

    // Permissions roles
    'authentication.permissions.list_role.name' => 'List',
    'authentication.permissions.create_role.name' => 'Create',
    'authentication.permissions.edit_role.name' => 'Edit',
    'authentication.permissions.delete_role.name' => 'Delete',
    'authentication.permissions.remove_product_from_role.name' => 'Remove product',
    'authentication.permissions.list_role.description' => 'Permission list role',
    'authentication.permissions.create_role.description' => 'Permission create role',
    'authentication.permissions.edit_role.description' => 'Permission edit role',
    'authentication.permissions.delete_role.description' => 'Permission delete role',
    // Permissions distributors
    'authentication.permissions.read_distributor.name' => 'Read',
    'authentication.permissions.view_distributor.name' => 'View',
    'authentication.permissions.list_distributor.name' => 'List',
    'authentication.permissions.create_distributor.name' => 'Create',
    'authentication.permissions.edit_distributor.name' => 'Edit',
    'authentication.permissions.add_product_distributor.name' => 'Add Product',
    'authentication.permissions.delete_distributor.name' => 'Delete',
    'authentication.permissions.remove_product_from_distributor.name' => 'Remove product',
    'authentication.permissions.read_distributor.description' => 'Permission read distributor',
    'authentication.permissions.view_distributor.description' => 'Permission view distributor',
    'authentication.permissions.list_distributor.description' => 'Permission list distributor',
    'authentication.permissions.create_distributor.description' => 'Permission create distributor',
    'authentication.permissions.edit_distributor.description' => 'Permission edit distributor',
    'authentication.permissions.delete_distributor.description' => 'Permission delete distributor',
    'authentication.permissions.add_product_distributor.description' => 'Permission add product distributor',
    'authentication.permissions.remove_product_from_distributor.description' => 'Permission remove product from distributor',

    // Permissions prices
    'authentication.permissions.view_price.name' => 'View',
    'authentication.permissions.list_price.name' => 'List',
    'authentication.permissions.create_price.name' => 'Create',
    'authentication.permissions.edit_price.name' => 'Edit',
    'authentication.permissions.delete_price.name' => 'Delete',

    // Permissions discount type
    'authentication.permissions.list_discount_type.name' => 'List',
    'authentication.permissions.create_discount_type.name' => 'Create',
    'authentication.permissions.edit_discount_type.name' => 'Edit',

    // Permissions credit accounts
    'authentication.permissions.list_credit_accounts.name' => 'List',
    'authentication.permissions.create_credit_account.name' => 'Create',
    'authentication.permissions.view_credit_account.name' => 'View',
    'authentication.permissions.edit_credit_account.name' => 'Edit',
    'authentication.permissions.delete_credit_account.name' => 'Delete',
    'authentication.permissions.list_credit_accounts.description' => 'Permission list credit_accounts',
    'authentication.permissions.create_credit_account.description' => 'Permission create credit_account',
    'authentication.permissions.edit_credit_account.description' => 'Permission edit credit_account',
    'authentication.permissions.delete_credit_account.description' => 'Permission delete credit_account',
    'authentication.permissions.view_credit_account.description' => 'Permission view credit_account',
    // Permissions specifications
    'authentication.permissions.list_specifications.name' => 'List',
    'authentication.permissions.create_specification.name' => 'Create',
    'authentication.permissions.view_specification.name' => 'View',
    'authentication.permissions.edit_specification.name' => 'Edit',
    'authentication.permissions.delete_specification.name' => 'Delete',
    'authentication.permissions.list_specifications.description' => 'Permission list specifications',
    'authentication.permissions.create_specification.description' => 'Permission create specification',
    'authentication.permissions.edit_specification.description' => 'Permission edit specification',
    'authentication.permissions.delete_specification.description' => 'Permission delete specification',
    'authentication.permissions.view_specification.description' => 'Permission view specification',
    // Permissions factories
    'authentication.permissions.list_factories.name' => 'List',
    'authentication.permissions.create_factory.name' => 'Create',
    'authentication.permissions.view_factory.name' => 'View',
    'authentication.permissions.edit_factory.name' => 'Edit',
    'authentication.permissions.delete_factory.name' => 'Delete',
    'authentication.permissions.list_factories.description' => 'Permission list factories',
    'authentication.permissions.create_factory.description' => 'Permission create factory',
    'authentication.permissions.edit_factory.description' => 'Permission edit factory',
    'authentication.permissions.delete_factory.description' => 'Permission delete factory',
    'authentication.permissions.view_factory.description' => 'Permission view factory',
    // Permissions areas
    'authentication.permissions.list_areas.name' => 'List',
    'authentication.permissions.create_area.name' => 'Create',
    'authentication.permissions.view_area.name' => 'View',
    'authentication.permissions.edit_area.name' => 'Edit',
    'authentication.permissions.delete_area.name' => 'Delete',
    'authentication.permissions.list_areas.description' => 'Permission list areas',
    'authentication.permissions.create_area.description' => 'Permission create area',
    'authentication.permissions.edit_area.description' => 'Permission edit area',
    'authentication.permissions.delete_area.description' => 'Permission delete area',
    'authentication.permissions.view_area.description' => 'Permission view area',
    // Permissions categories
    'authentication.permissions.list_categories.name' => 'List',
    'authentication.permissions.create_category.name' => 'Create',
    'authentication.permissions.edit_category.name' => 'Edit',
    'authentication.permissions.delete_category.name' => 'Delete',
    'authentication.permissions.list_categories.description' => 'Permission list categories',
    'authentication.permissions.create_category.description' => 'Permission create category',
    'authentication.permissions.edit_category.description' => 'Permission edit category',
    'authentication.permissions.delete_category.description' => 'Permission delete category',
    // Permissions brands
    'authentication.permissions.list_brands.name' => 'List',
    'authentication.permissions.create_brand.name' => 'Create',
    'authentication.permissions.edit_brand.name' => 'Edit',
    'authentication.permissions.delete_brand.name' => 'Delete',
    'authentication.permissions.list_brands.description' => 'Permission list brands',
    'authentication.permissions.create_brand.description' => 'Permission create brand',
    'authentication.permissions.edit_brand.description' => 'Permission edit brand',
    'authentication.permissions.delete_brand.description' => 'Permission delete brand',
    // Permissions stores
    'authentication.permissions.list_stores.name' => 'List',
    'authentication.permissions.create_store.name' => 'Create',
    'authentication.permissions.edit_store.name' => 'Edit',
    'authentication.permissions.delete_store.name' => 'Delete',
    'authentication.permissions.list_stores.description' => 'Permission list stores',
    'authentication.permissions.create_store.description' => 'Permission create store',
    'authentication.permissions.edit_store.description' => 'Permission edit store',
    'authentication.permissions.delete_store.description' => 'Permission delete store',
    // Permissions products
    'authentication.permissions.list_products.name' => 'List',
    'authentication.permissions.create_product.name' => 'Create',
    'authentication.permissions.edit_product.name' => 'Edit',
    'authentication.permissions.delete_product.name' => 'Delete',
    'authentication.permissions.list_products.description' => 'Permission list products',
    'authentication.permissions.create_product.description' => 'Permission create product',
    'authentication.permissions.edit_product.description' => 'Permission edit product',
    'authentication.permissions.delete_product.description' => 'Permission delete product',
    // Permissions orders
    'authentication.permissions.list_orders.name' => 'List',
    'authentication.permissions.create_order.name' => 'Create',
    'authentication.permissions.edit_order.name' => 'Edit',
    'authentication.permissions.delete_order.name' => 'Delete',
    'authentication.permissions.list_orders.description' => 'Permission list orders',
    'authentication.permissions.create_order.description' => 'Permission create order',
    'authentication.permissions.edit_order.description' => 'Permission edit order',
    'authentication.permissions.delete_order.description' => 'Permission delete order',
    'authentication.permissions.reject_item_product_order.name' => 'Reject item product',
    'authentication.permissions.reject_item_product_order.description' => 'Reject item product',
    'authentication.permissions.update_order.name' => 'Update',
    'authentication.permissions.update_order.description' => 'Permission update order',
    'authentication.permissions.approve_order.name' => 'Approved',
    'authentication.permissions.approve_order.description' => 'Permission Approved Order',
    'authentication.permissions.review_order.name' => 'Review',
    'authentication.permissions.review_order.description' => 'Permission Review Order',
    'authentication.permissions.reject_order.name' => 'Reject',
    'authentication.permissions.reject_order.description' => 'Permission Reject Order',
    // Permissions Admins
    'authentication.permissions.list_admins.name' => 'List',
    'authentication.permissions.create_admin.name' => 'Create',
    'authentication.permissions.view_admin.name' => 'View',
    'authentication.permissions.edit_admin.name' => 'Edit',
    'authentication.permissions.delete_admin.name' => 'Delete',
    'authentication.permissions.list_admin.description' => 'Permission list admins',
    'authentication.permissions.create_admin.description' => 'Permission create admin',
    'authentication.permissions.edit_admin.description' => 'Permission edit admin',
    'authentication.permissions.delete_admin.description' => 'Permission delete admin',
    'authentication.permissions.view_admin.description' => 'Permission view admin',
    // Permissions Members
    'authentication.permissions.list_members.name' => 'List',
    'authentication.permissions.create_member.name' => 'Create',
    'authentication.permissions.view_member.name' => 'View',
    'authentication.permissions.edit_member.name' => 'Edit',
    'authentication.permissions.delete_member.name' => 'Delete',
    'authentication.permissions.list_members.description' => 'Permission list members',
    'authentication.permissions.create_member.description' => 'Permission create member',
    'authentication.permissions.edit_member.description' => 'Permission edit member',
    'authentication.permissions.delete_member.description' => 'Permission delete member',
    'authentication.permissions.view_member.description' => 'Permission view member',
    // Permissions UOMs
    'authentication.permissions.list_uoms.name' => 'List',
    'authentication.permissions.create_uom.name' => 'Create',
    'authentication.permissions.edit_uom.name' => 'Edit',
    'authentication.permissions.delete_uom.name' => 'Delete',
    'authentication.permissions.list_uoms.description' => 'Permission list uoms',
    'authentication.permissions.create_uom.description' => 'Permission create uom',
    'authentication.permissions.edit_uom.description' => 'Permission edit uom',
    'authentication.permissions.delete_uom.description' => 'Permission delete uom',
    // Permissions UOM Multiples
    'authentication.permissions.list_uom_multiples.name' => 'List',
    'authentication.permissions.create_uom_multiple.name' => 'Create',
    'authentication.permissions.edit_uom_multiple.name' => 'Edit',
    'authentication.permissions.delete_uom_multiple.name' => 'Delete',
    'authentication.permissions.list_uom_multiples.description' => 'Permission list uom_multiples',
    'authentication.permissions.create_uom_multiple.description' => 'Permission create uom_multiple',
    'authentication.permissions.edit_uom_multiple.description' => 'Permission edit uom_multiple',
    'authentication.permissions.delete_uom_multiple.description' => 'Permission delete uom_multiple',
    // Permissions PRODUCT Types
    'authentication.permissions.list_product_types.name' => 'List',
    'authentication.permissions.create_product_type.name' => 'Create',
    'authentication.permissions.edit_product_type.name' => 'Edit',
    'authentication.permissions.delete_product_type.name' => 'Delete',
    'authentication.permissions.list_product_types.description' => 'Permission list product_types',
    'authentication.permissions.create_product_type.description' => 'Permission create product_type',
    'authentication.permissions.edit_product_type.description' => 'Permission edit product_type',
    'authentication.permissions.delete_product_type.description' => 'Permission delete product_type',
    // Permissions GRADEs
    'authentication.permissions.list_grades.name' => 'List',
    'authentication.permissions.create_grade.name' => 'Create',
    'authentication.permissions.edit_grade.name' => 'Edit',
    'authentication.permissions.delete_grade.name' => 'Delete',
    'authentication.permissions.list_grades.description' => 'Permission list grades',
    'authentication.permissions.create_grade.description' => 'Permission create grade',
    'authentication.permissions.edit_grade.description' => 'Permission edit grade',
    'authentication.permissions.delete_grade.description' => 'Permission delete grade',
    // Permissions GRADE Groups
    'authentication.permissions.list_grade_groups.name' => 'List',
    'authentication.permissions.create_grade_group.name' => 'Create',
    'authentication.permissions.edit_grade_group.name' => 'Edit',
    'authentication.permissions.delete_grade_group.name' => 'Delete',
    'authentication.permissions.list_grade_groups.description' => 'Permission list grade_groups',
    'authentication.permissions.create_grade_group.description' => 'Permission create grade_group',
    'authentication.permissions.edit_grade_group.description' => 'Permission edit grade_group',
    'authentication.permissions.delete_grade_group.description' => 'Permission delete grade_group',
    // Permissions FEATUREs
    'authentication.permissions.list_features.name' => 'List',
    'authentication.permissions.create_feature.name' => 'Create',
    'authentication.permissions.edit_feature.name' => 'Edit',
    'authentication.permissions.delete_feature.name' => 'Delete',
    'authentication.permissions.list_features.description' => 'Permission list features',
    'authentication.permissions.create_feature.description' => 'Permission create feature',
    'authentication.permissions.edit_feature.description' => 'Permission edit feature',
    'authentication.permissions.delete_feature.description' => 'Permission delete feature',
    // Permissions FEATURE_ITEMs
    'authentication.permissions.list_feature_items.name' => 'List',
    'authentication.permissions.create_feature_item.name' => 'Create',
    'authentication.permissions.edit_feature_item.name' => 'Edit',
    'authentication.permissions.delete_feature_item.name' => 'Delete',
    'authentication.permissions.list_feature_items.description' => 'Permission list feature_items',
    'authentication.permissions.create_feature_item.description' => 'Permission create feature_item',
    'authentication.permissions.edit_feature_item.description' => 'Permission edit feature_item',
    'authentication.permissions.delete_feature_item.description' => 'Permission delete feature_item',
    // Permissions ATTRIBUTEs
    'authentication.permissions.list_attributes.name' => 'List',
    'authentication.permissions.create_attribute.name' => 'Create',
    'authentication.permissions.edit_attribute.name' => 'Edit',
    'authentication.permissions.delete_attribute.name' => 'Delete',
    'authentication.permissions.list_attributes.description' => 'Permission list attributes',
    'authentication.permissions.create_attribute.description' => 'Permission create attribute',
    'authentication.permissions.edit_attribute.description' => 'Permission edit attribute',
    'authentication.permissions.delete_attribute.description' => 'Permission delete attribute',
    // Permissions ATTRIBUTE_LIST_OF_VALUEs
    'authentication.permissions.list_attribute_list_of_values.name' => 'List',
    'authentication.permissions.create_attribute_list_of_value.name' => 'Create',
    'authentication.permissions.edit_attribute_list_of_value.name' => 'Edit',
    'authentication.permissions.delete_attribute_list_of_value.name' => 'Delete',
    'authentication.permissions.list_attribute_list_of_values.description' => 'Permission list attribute_list_of_values',
    'authentication.permissions.create_attribute_list_of_value.description' => 'Permission create attribute_list_of_value',
    'authentication.permissions.edit_attribute_list_of_value.description' => 'Permission edit attribute_list_of_value',
    'authentication.permissions.delete_attribute_list_of_value.description' => 'Permission delete attribute_list_of_value',
    // Permissions PO
    'authentication.permissions.list_all_po_distributor.name' => 'List',
    'authentication.permissions.create_po_distributor.name' => 'Create',
    'authentication.permissions.edit_po_distributor.name' => 'Edit',
    'authentication.permissions.delete_po_distributor.name' => 'Delete',
    'authentication.permissions.read_po_order.name' => 'Read',
    'authentication.permissions.read_po_order.description' => 'Permission read po',

    // Permissions SO
    'authentication.permissions.list_all_so_distributor.name' => 'List',
    'authentication.permissions.create_so_distributor.name' => 'Create',
    'authentication.permissions.edit_so_distributor.name' => 'Edit',
    'authentication.permissions.delete_so_distributor.name' => 'Delete',

    // Permissions DN
    'authentication.permissions.list_all_dn_distributor.name' => 'List',
    'authentication.permissions.create_dn_distributor.name' => 'Create',
    'authentication.permissions.edit_dn_distributor.name' => 'Edit',
    'authentication.permissions.delete_dn_distributor.name' => 'Delete',
    'authentication.permissions.update_dn_distributor.name' => 'Update',
    'authentication.permissions.update_dn_distributor.description' => 'Update',
    'authentication.permissions.confirm_update_dn_distributor.name' => 'Confirm Update',
    'authentication.permissions.confirm_update_dn_distributor.description' => 'Confirm Update',
    'authentication.permissions.reverse_dn_distributor.name' => 'Reverse',
    'authentication.permissions.reverse_dn_distributor.description' => 'Reverse',
    'authentication.permissions.approve_dn_distributor.name' => 'Approved',
    'authentication.permissions.approve_dn_distributor.description' => 'Approved',
    'authentication.permissions.reject_dn_distributor.name' => 'Reject',
    'authentication.permissions.reject_dn_distributor.description' => 'Reject',
    //attributes
    'store.attributes.save.existing.app_error' => 'Must call update for exisiting attributes',
    'store.attributes.save.app_error' => 'Unable to save the attributes',
    'model.attributes.is_valid.id.app_error' => 'Invalid Id',
    'store.attributes.update.find.app_error' => 'Unable to find the existing attributes to update',
    'store.attributes.update.app_error' => 'Unable to update the attributes',
    'store.attributes.update.updating.app_error' => 'We encountered an error updating the attributes',
    'store.stores.product.type.find.app_error' => 'Unable to find the existing product type',
    'store.stores.attribute.lists.of.value.find.app_error' => 'Unable to find the existing attribute lists of value',
    //end attributes

    //attributes
    'store.attribute.list.of.value.save.existing.app_error' => 'Must call update for exisiting attribute list of value',
    'store.attribute.list.of.value.save.app_error' => 'Unable to save the attribute list of value',
    'model.attribute.list.of.value.is_valid.id.app_error' => 'Invalid Id',
    'store.attribute.list.of.value.update.find.app_error' => 'Unable to find the existing attribute list of value to update',
    'store.attribute.list.of.value.update.app_error' => 'Unable to update the attribute list of value',
    'store.attribute.list.of.value.update.updating.app_error' => 'We encountered an error updating the attribute list of value',
    'store.stores.attributes.find.app_error' => 'Unable to find the existing attribute',
    //end attributes

    //price list
    'store.price.list.of.value.save.existing.app_error' => 'Must call update for exisiting price list of value',
    'store.price.list.of.value.save.app_error' => 'Unable to save the price list of value',
    'model.price.list.of.value.is_valid.id.app_error' => 'Invalid Id',
    'store.price.list.of.value.update.find.app_error' => 'Unable to find the existing price list of value to update',
    'store.price.list.of.value.update.app_error' => 'Unable to update the price list of value',
    'store.price.list.of.value.update.updating.app_error' => 'We encountered an error updating the price list of value',
    //end price list

    // Sale order
    'store.sale_order_store.get_sale_order_items_by_ids.invalid' => 'Invalid params saleOrderItemIds',
    'store.sale_order_store.get_sale_order_items_by_ids.app_error' => 'We encountered an error finding the items that sale man have ordered',
    'store.sale_order_store.get.get_by_multiple_ids.app_error' => 'We encountered an error finding the items that sale man have ordered',
    'store.sale_order_store.get_sale_order_item_by_id.app_error' => 'We encountered an error finding the item that sale man have ordered',
    'store.sale_order_store.get.finding.app_error' => 'We encountered an error finding the sale order',
    'store.sale_order_store.get.finded.app_error' => 'Cannot find the sale order',
    'store.stores.sale.order.find.app_error' => 'Unable to find the existing sale order to create and update',
    'store.sale.order.save.app_error' => 'Unable to save the sale order',
    'store.sale_order_store.get_sale_order_item_by_ids.not_found.app_error' => 'Cannot finding sale order items',
    'api.quantity.sale_order_item.app_error' => 'Sale Quantity cannot more than the origin sale items',

    // delivery note
    'store.delivery_note.finding.app_error' => 'We encountered an error finding the delivery note',
    'api.quantity.sale_order_item.app_error' => 'Delivery Quantity cannot more than Sale order Quantity',
    'api.quantity.sale_order_item.reverse.app_error' => 'Delivery Quantity cannot more than the origin delivery note',
    'store.delivery_note.finded.app_error' => 'Cannot finding the delivery note',
    'store.delivery_note.deleting.app_error' => 'We encountered an error deleting the delivery note',
    'store.delivery_note.deleted.app_error' => 'Cannot delete the delivery note',
    'delivery_note_repository.confirm.in_valid' => 'This delivery note cannot be confirmed',
    'delivery_note_repository.reverse.in_valid' => 'This delivery note cannot be reversed',
    'model.count_distributors_from_so_ids_for_dn.app_error' => 'Cannot create delivery from multiple distributors',
    'model.count_factories_from_so_ids_for_dn.app_error' => 'Cannot create delivery from multiple factories',
    'model.count_so_items_by_so_ids.not_count' => 'Cannot create delivery note without items',
    'delivery_note_repository.count_so_items_by_so_ids.app_error' => 'Sorry, we have some problems when handling delivery notes. Please try again or contact us',
    'store.delivery_note_store.update_or_create.up_sert.app_error' => 'Unable to save or update the delivery note',
    'model.dn.from.so.not_allow' => 'Cannot create delivery note from the sale_order that not openning',
    'model.update_dn.from.not_draft.not_allow' => 'Cannot update delivery note that are not draft',
    'model.dn_item.from.so.not_allow' => 'Cannot create delivery note from the sale_order_item that not allow',
    'model.dn_item.from.so.exceed_remaining' => 'The devlivery note quantity on that item is exceed the remaining quantity in sale order, expect to :Quantity',
    'model.dn.from.so.not_allow_from_multiple_distributor' => 'Not allow create delivery notes from multiple distributor',
    'controller.dn.sale_order_items.get_by_ids.not_found' => 'Sorry, we cannot execute this request. Something wrong',
    'delivery_note.model.confirm.invalid.status' => 'Cannot confirm on this delivery note when current status is :Status',
    // transaction
    'up_sert_credit_transaction_with_tr' => 'Cannot create or update transaction',
    'store.transaction_store.up_sert_credit_account_with_tr.app_error' => 'Cannot create or update credit account',
    'store.transaction_store.finding.app_error' => 'We encountered an error finding the credit account for distributor',
    'store.transaction_store.finded.app_error' => 'We encountered an error finding the credit account for distributor',
    'store.transaction_store.update_dr_credit_account.app_error' => 'Cannot update credit account',
    'store.transaction_store.exceed_min_credit_account.app_error' => 'This transaction exceed your credit limit',
    'store.transaction_store.save.existing.app_error' => 'Must call update for exisiting credit transaction',
    'store.transaction_store.save.app_error' => 'Unable to save the credit transaction',
    'store.store.credit_acount.update.find.app_error' => 'Unable to find the existing credit acount to update',
    //end transaction
    //credit account
    'store.credit_account.get_credit_account_by_id.app_error' => 'We encountered an error finding the id that credit account',
    'store.credit_account.update_amount_false.app_error' => 'Update amount about table credit account fail',
    'store.credit_account.available_amount_less_credit_limit.app_error' => 'Update amount about table credit account fail why available amount less credit limit',
    'store.credit_account.amount_less_credit_limit.app_error' => 'Update amount about table credit account fail why  amount less credit limit',
    'store.credit_account.save.app_error' => 'Sorry, transaction error! Please try again or contact us',
    //end credit account

    //discount type
    'api.min.discount.type.app_error' => 'The :Name  small :Number',
    'api.numberic.discount.type.app_error' => 'The :Name can type numberic',
    'api.max.discount.type.app_error' => 'The :Name  biggest :Number',
    'store.discount.type.save.existing.app_error' => 'Must call update for exisiting discount type',
    'store.discount.type.save.app_error' => 'Unable to save the discount type',
    'model.discount.type.is_valid.id.app_error' => 'Invalid Id',
    'store.discount.type.update.find.app_error' => 'Unable to find the existing discount type to update',
    'store.discount.type.update.app_error' => 'Unable to update the discount type',
    'store.discount.type.update.updating.app_error' => 'We encountered an error updating the discount type',

    //end discount type
];
