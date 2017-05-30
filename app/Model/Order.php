<?php
App::uses('Model', 'Model');

class Order extends Model
{
    public $name = "Order";
    public $hasMany = array(
        'ShoppingCart' => array(
            'className' => 'ShoppingCart',
            'foreignKey' => 'order_id',
            'dependent' => true));
    public $hasOne = array(
        'Review' => array(
            'className' => 'Review',
            'foreignKey' => 'order_id',
            'dependent' => true),
        'StripeRefund' => array(
            'className' => 'StripeRefund',
            'foreignKey' => 'order_id',
            'dependent' => true));

    public $belongsTo = array(
        'Customer' => array(
            'className' => 'Customer',
            'foreignKey' => 'customer_id',
            'dependent' => true),
        'Store' => array(
            'className' => 'Store',
            'foreignKey' => 'store_id',
            'dependent' => true),
        'Driver' => array(
            'className' => 'Driver',
            'foreignKey' => 'driver_id',
            'dependent' => true));

}
