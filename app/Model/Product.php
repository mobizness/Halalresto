<?php
App::uses('Model', 'Model');

class Product extends Model
{
    public $name = "Product";
    public $belongsTo = array(
        'Store' => array(
            'className' => 'Store',
            'foreignKey' => 'store_id',
            'dependent' => true),
        'MainCategory' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id',
            'dependent' => true));
    public $hasMany = array(
        'ProductDetail' => array('className' => 'ProductDetail',
            'foreignKey' => 'product_id',
            'dependent' => true),
        /*'ProductImage' => array('className' => 'ProductImage',
            'foreignKey' => 'product_id',
            'dependent' => true),*/
        'ProductAddon' => array('className' => 'ProductAddon',
            'foreignKey' => 'product_id',
            'dependent' => true));
    public $hasOne = array(
        'Deal' => array('className' => 'Deal',
            'foreignKey' => 'main_product',
            'dependent' => true));

    var $validate = array(
        'store_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please Select the store'
            )
        ),
        'product_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le nom du produit svp'
            )
        ),
        'category_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Sélectionnez le Catégorie svp'
            )
        ),
        'price_option' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Sélectionner la ville SVP'
            )
        )
    );

}
