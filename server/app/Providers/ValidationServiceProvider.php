<?php

namespace App\Providers;

use App\App\DeliveryNoteRepository;
use App\App\DistributorRepository;
use App\App\SaleOrderRepository;
use App\Models\Error;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the validation services for the application.
     *
     * @return void
     */
    public function boot()
    {
        app('translator')->setLocale('en');
        /* Validation username format */
        Validator::extend('username_format', function ($attr, $value, $parameters) {
            return preg_match('/^[a-zA-Z0-9]+$/', $value);
        }, trans('Invalid username format.'));

        Validator::extend('validate_amount', function ($attr, $value, $parameters) {
            if ($value > 10000) {
                return false;
            } else {
                return true;
            }
        }, trans('OUT OF STOCK.'));

        /* Validation password format:
           - English uppercase characters (A – Z)
           - English lowercase characters (a – z)
           - Base 10 digits (0 – 9)
           - Non-alphanumeric (For example: !, $, #, or %)
           - Unicode characters
        */
        Validator::extend('password_complex', function ($attr, $value, $parameters) {
            return preg_match('/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/', $value);
        }, trans('Invalid password format.'));

        /* Validation price format */
        Validator::extend('price_format', function ($attr, $value, $parameters) {
            return preg_match('/^\d*(\.\d{1,2})?$/', $value);
        }, trans('Invalid price format.'));

        /* Validation no space */
        Validator::extend('no_space', function ($attr, $value, $parameters) {
            return preg_match('/^\S*$/u', $value);
        }, trans('Field must not have spaces.'));

        /* Validation string is ASCII */
        Validator::extend('is_ascii', function ($attr, $value, $parameters, $validator) {
            if (strlen($value) != strlen(utf8_decode($value))) {
                return false;
            } else {
                return true;
            }
        });
        Validator::extend('check_remaining_quantity', function ($attr, $value, $parameters) {
            $index = explode(".", $attr)[1];

            $input = app('request')->input();
            if (isset($input['from_sale_order_items']) && $input['from_sale_order_items']) {
                if (isset($input['from_sale_order_items'][$index])) {
                    $item = $input['from_sale_order_items'][$index];
                    if (isset($item['so_item_id'])) {
                        $soItem = (new SaleOrderRepository())->getSaleOrderItemById($item['so_item_id']);
                        if (is_object($soItem) && get_class($soItem) == Error::class) {
                            return false;
                        }
                        if (!$soItem) {
                            return false;
                        }
                        if ($soItem->remaining_quantity < $value) {
                            return false;
                        }
                    }
                }
            }
            return true;
        });
        Validator::extend('check_can_reverse', function ($attr, $value, $parameters) {
            $index = explode(".", $attr)[1];
            $parameters = (int)$parameters[0];
            $deliverNote = (new DeliveryNoteRepository())->getDeliveryNote($parameters);
            if (is_object($deliverNote) && get_class($deliverNote) == Error::class ) {
                return false;
            }
            if (!$deliverNote) {
                return false;
            }
            $input = app('request')->input();
            if (isset($input['from_sale_order_items']) && $input['from_sale_order_items']) {
                if (isset($input['from_sale_order_items'][$index])) {
                    $item = $input['from_sale_order_items'][$index];
                    if (isset($item['dn_item_id'])) {
                        foreach ($deliverNote->items as $dnItem) {
                            if ($dnItem->id == $item['dn_item_id']) {
                                $oldQuantity = 0;

                                foreach ($dnItem->reverse_items as $reverse_item) {
                                    $oldQuantity += $reverse_item->deliver_quantity;
                                }
                                if ($item['deliver_quantity'] > ($dnItem->deliver_quantity - $oldQuantity)) {
                                    return false;
                                }
                            }
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
            return true;
        });

        Validator::extend('check_sale_quantity', function ($attr, $value, $parameters) {
            $index = explode(".", $attr)[1];
            $parameters = (int)$parameters[0];

            $input = app('request')->input();
            if (isset($input['items']) && $input['items']) {
                if (isset($input['items'][$index])) {
                    $item = $input['items'][$index];
                    if($item['sale_quantity'] > $item['customer_quantity']) {
                        return false;
                    }
                } else {
                    return false;
                }
            }

            return true;

        });

        Validator::extend('check_sale_quantity_min', function ($attr, $value, $parameters) {
            $index = explode(".", $attr)[1];
            $parameters = (int)$parameters[0];

            $input = app('request')->input();
            if (isset($input['items']) && $input['items']) {
                if (isset($input['items'][$index])) {
                    $item = $input['items'][$index];
                    if($item['sale_quantity'] <= 0) {
                        return false;
                    }
                } else {
                    return false;
                }
            }

            return true;

        });

        Validator::extend('check_min_quantity', function ($attr, $value, $parameters) {
            $index = explode(".", $attr)[1];
            $input = app('request')->input();
            if (isset($input['items']) && $input['items']) {
                if (isset($input['items'][$index])) {
                    $item = $input['items'][$index];
                    $min = (new DistributorRepository())->productGetDistributor($item['product_id'], $item['distributor_id']);
                    if(count($min) > 0) {
                        if($item['amount'] < $min[0]['min_quantity']) {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            }

            return true;

        });
    }
}
