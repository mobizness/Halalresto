<?php
App::uses('Model', 'Model');

class ProductDetail extends Model
{
    public $name = "ProductDetail";
    public $belongsTo = array(
        'Product' => array('className' => 'Product',
            'foreignKey' => 'product_id',
            'dependent' => true));
}
