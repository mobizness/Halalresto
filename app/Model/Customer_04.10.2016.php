<?php
App::uses('Model', 'Model');

class Customer extends Model
{
    //public $name = "Customer";
    public $belongsTo = array(
        'User' => array('className' => 'User',
            'foreignKey' => 'user_id',
            'dependent' => true));
    public $hasMany = array(
        'CustomerAddressBook' => array('className' => 'CustomerAddressBook',
            'foreignKey' => 'customer_id',
            'dependent' => true),
        'Order' => array('className' => 'Order',
            'foreignKey' => 'customer_id',
            'dependent' => true));


    var $validate = array(
        'first_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter firstname'
            )
        ),
        'last_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter lastname'
            )
        ),
        'customer_email' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter email'
            ),
            'validEmailRule' => array(
                'rule' => array('email'),
                'message' => 'Please enter a valid email address'
            ),
        ),
        'password' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter password'
            )
        ),
        'confir_password' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter confirm password'
            )
        ),
        'customer_phone' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter phone number'
            ),
            'phone_no_should_be_numeric' => array(
                'rule' => 'numeric',
                'message' => 'Please enter valid phone number'
            )
        )

    );
}
