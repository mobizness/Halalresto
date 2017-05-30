<?php
App::uses('Model', 'Model');

class CustomerAddressBook extends Model
{
    public $name = "CustomerAddressBook";
    public $belongsTo = array(
        'State' => array('className' => 'State',
            'foreignKey' => 'state_id',
            'dependent' => true),
        'Customer' => array('className' => 'Customer',
            'foreignKey' => 'customer_id',
            'dependent' => true),
        'Location' => array('className' => 'Location',
            'foreignKey' => 'location_id',
            'dependent' => true),
        'City' => array('className' => 'City',
            'foreignKey' => 'city_id',
            'dependent' => true),
    );



    var $validate = array(
        'address_title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter tittle'
            )
        ),
        /*'address' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter street address
'
            )
        ),*/
        'customer_phone' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter phone number'
            ),
            'phone_no_should_be_numeric' => array(
                'rule' => 'numeric',
                'message' => 'Please enter valid phone number'
            )
        ),
        /*'landmark' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter landmark'
            )
        ),
        'state_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please select state'
            )
        ),
        'city_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please select city'
            )
        ),
        'location_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please select location'
            )
        ),*/

    );


}
