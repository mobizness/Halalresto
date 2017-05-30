<?php

/* MN */

App::uses('Model', 'Model');

class Orderstatus extends AppModel
{

    public $belongsTo = array(
        'Order' => array('className' => 'Order',
            'foreignKey' => 'order_id',
            'dependent' => true),
        'Driver' => array('className' => 'Driver',
            'foreignKey' => 'driver_id',
            'dependent' => true));
}