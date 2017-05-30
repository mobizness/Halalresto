<?php

/**
 * Created by PhpStorm.
 * User: admin6
 * Date: 5/2/16
 * Time: 9:43 AM
 */
class StoreCuisine extends AppModel
{
    public $belongsTo = array(
        'Cuisine' => array('className' => 'Cuisine',
            'foreignKey' => 'cuisine_id',
            'dependent' => true),
        'Store' => array('className' => 'Store',
            'foreignKey' => 'store_id',
            'dependent' => true));
}