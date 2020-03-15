<?php
namespace App\Models\Store;

class SearchProducts {
    public $factory_id;

    public $code;

    public $featureitem_id;

    public $category_id;

    public $limit;

    public static function toModel($data) {
        $fields = get_class_vars(self::class);

        $productSearch = new self();

        foreach ($fields as $key => $value) {
            if (isset($data[$key])) {
                $productSearch->$key = $data[$key];
            }
        }

        return $productSearch;
    }
}
