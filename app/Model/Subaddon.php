<?php
App::uses('Model', 'Model');


class Subaddon extends Model
{
    public $belongsTo = [
        'Mainaddon' => [
            'className' => 'Mainaddon',
            'foreignKey' => 'mainaddons_id',
            'dependent' => true
        ]
    ];
    /*public $hasMany = [
        'ProductAddon' => [
            'className' => 'ProductAddon',
            'foreignKey' => 'subaddons_id',
            'dependent' => true
        ]
    ];*/
}