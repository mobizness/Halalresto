<?php

App::uses('Model', 'Model');

class Category extends Model {

    public $belongsTo = [
        'Store' => [
            'className' => 'Store',
            'foreignKey' => 'store_id',
            'dependent' => true
        ]
    ];
    
    var $validate = array(
        'category_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le nom du Catégorie svp'
            )
        )
    );

}
