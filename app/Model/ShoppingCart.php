<?php

/* MN */

App::uses('Model', 'Model');


class ShoppingCart extends AppModel
{


    public $belongsTo = array(
        'ProductDetail' => array('className' => 'ProductDetail',
            'foreignKey' => 'product_id',
            'dependent' => true),
        'Store' => array('className' => 'Store',
            'foreignKey' => 'store_id',
            'dependent' => true),
        'ProductImage' => array('className' => 'ProductImage',
            'foreignKey' => 'product_id',
            'dependent' => true));
}