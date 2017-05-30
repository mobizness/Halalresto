<?php

/* MN */

App::uses('Model', 'Model');

class Driver extends AppModel
{

    public $belongsTo = array(
        'User' => array('className' => 'User',
            'foreignKey' => 'parent_id',
            'dependent' => true));

    public $hasOne = array(
        'DriverTracking' => array(
            'className' => 'DriverTracking',
            'foreignKey' => 'driver_id',
            'dependent' => true),
        'Vehicle' => array(
            'className' => 'Vehicle',
            'foreignKey' => 'driver_id',
            'dependent' => true));

    var $validate = array(
        'driver_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the driver name'
            )
        ),
        'driver_email' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the email'
            ),
            'validEmailRule' => array(
                'rule' => array('email'),
                'message' => 'Please enter a valid email address'
            ),
        ),
        'address' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the address'
            )
        ),
        'license_no' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the license no'
            )
        ),
        'gender' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please choose the gender'
            )
        )
    );
}