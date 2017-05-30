<?php

App::uses('Model', 'Model');

class Invoice extends Model
{
    public $name = "Invoice";
    public $belongsTo = array(
        'Store' => array('className' => 'Store',
            'foreignKey' => 'store_id',
            'dependent' => true));


}
