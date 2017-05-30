<?php

App::uses('Model', 'Model');

class BookaTable extends Model {

	//public $name = "Customer";
    public $belongsTo = array(
        'Store' => array('className' => 'Store',
            'foreignKey' => 'store_id',
            'dependent' => true));


}
