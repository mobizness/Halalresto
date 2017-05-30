<?php

/* MN */

App::uses('Model', 'Model');

class StoreDeliveryArea extends Model {

    var $name = 'StoreDeliveryArea';
    var $useTable = 'store_delivery_areas';
    public $belongsTo = array(
        'Store' => array(
            'className' => 'Store',
            'foreignKey' => 'store_id',
            'dependent' => true));
    public $hasOne = array(
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id',
            'dependent' => true));

}
