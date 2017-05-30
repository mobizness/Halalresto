<?php

App::uses('Model', 'Model');

class Country extends Model
{
    public $name = "Country";

    var $validate = array(
        'country_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le nom de la pays svp'
            )
        ),
        'iso' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le ISO svp'
            )
        ),
        'phone_code' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please Enter Phone code'
            ),
            'phone_no_should_be_numeric' => array(
                'rule' => 'numeric',
                'message' => 'Please enter valid phone code'
            )
        ),
        'currency_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please Enter currencyname'
            )
        ),
        'currency_code' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please Enter Currency code'
            )
        ),
        'currency_symbol' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please Enter Currency Symbol'
            )
        )
	);
}
