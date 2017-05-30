<?php

App::uses('Model', 'Model');

class Review extends Model
{
    public $name = "Review";
    public $belongsTo = array(
        'Store' => array('className' => 'Store',
            'foreignKey' => 'store_id',
            'dependent' => true),
        'Order' => array('className' => 'Order',
            'foreignKey' => 'order_id',
            'dependent' => true));


}
