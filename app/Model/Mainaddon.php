<?php
App::uses('Model', 'Model');


class Mainaddon extends Model
{
    public $hasMany = [
        'Subaddon' => [
            'className' => 'Subaddon',
            'foreignKey' => 'mainaddons_id',
            'dependent' => true
        ]
    ];

    public $belongsTo = [
        'Store' => [
            'className' => 'Store',
            'foreignKey' => 'store_id',
            'dependent' => true
        ],
        'Category' => [
            'className' => 'Category',
            'foreignKey' => 'category_id',
            'dependent' => true
        ]
    ];
}