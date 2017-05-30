<?php
App::uses('Model', 'Model');

class State extends Model
{
    public $name = "State";
    public $belongsTo = array(
        'Country' => array('className' => 'Country',
            'foreignKey' => 'country_id',
            'dependent' => true));

    var $validate = array(
        'country_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Sélectionnez le pays svp'
            )
        ),
        'state_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the state name'
            )
        )
	);
}
