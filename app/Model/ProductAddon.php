<?php

/**
 * Created by Manikandan N.
 * User: admin6
 * Date: 5/21/16
 * Time: 4:17 PM
 */
class ProductAddon extends Model
{
    public $name = "ProductAddon";
    public $belongsTo = [
        'Mainaddon' => [
            'className' => 'Mainaddon',
            'foreignKey' => 'mainaddons_id',
            'dependent' => true
        ],
        'Subaddon' => [
            'className' => 'Subaddon',
            'foreignKey' => 'subaddons_id',
            'dependent' => true
        ]
    ];
}