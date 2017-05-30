<?php

/* MN */

App::uses('Model', 'Model');

class Store extends AppModel
{

    public $belongsTo = array(
        'User' => array('className' => 'User',
            'foreignKey' => 'user_id',
            'dependent' => true));

    public $hasMany = array(
        'DeliveryLocation' => array(
            'className' => 'DeliveryLocation',
            'foreignKey' => 'store_id',
            'dependent' => true),
        'Storeoffer' => array(
            'className' => 'Storeoffer',
            'foreignKey' => 'store_id',
            'dependent' => true),
        'Invoice' => array(
            'className' => 'Invoice',
            'foreignKey' => 'store_id',
            'dependent' => true),
        'Review' => array(
            'className' => 'Review',
            'foreignKey' => 'store_id',
            'dependent' => true),
        'StoreCuisine' => array(
            'className' => 'StoreCuisine',
            'foreignKey' => 'store_id',
            'dependent' => true));
    public $hasOne = array(
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'store_id',
            'dependent' => true),
        'StoreTiming' => array(
            'className' => 'StoreTiming',
            'foreignKey' => 'store_id',
            'dependent' => true));

    var $validate = array(
        'contact_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le nom du contact svp'
            )
        ),
        'contact_phone' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please contact phone'
            )
        ),
        'contact_email' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter email'
            ),
            'validEmailRule' => array(
                'rule' => array('email'),
                'message' => 'Entrez une adresse email valide'
            )
        ),
        /*'street_address' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter street address'
            )
        ),
        'store_state' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please select the state'
            )
        ),
        'store_city' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please select the city'
            )
        ),
        'store_zip' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please select the zipcode/area name'
            )
        ),*/
        'store_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le nom du restaurant svp'
            )
        ),
        'store_phone' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez votre numéro de téléphone SVP'
            ),
            'phone_no_should_be_numeric' => array(
                'rule' => 'numeric',
                'message' => 'Please enter valid phone number'
            )
        ),
        'dispatch' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please choose the option'
            )
        ),
        'collection' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please choose the option'
            )
        ),
        'delivery' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please choose the option'
            )
        ),
        /*'delivery_option' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please select the zipcode/area name'
            )
        ),
        'location_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please select atlease 1 location'
            )
        ),*/
        'minimum_order' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le montant minimum de la commande svp'
            )
        ),
        'tax' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le T.V.A svp'
            ),
            'tax_no_should_be_numeric' => array(
                'rule' => 'numeric',
                'message' => 'Please enter valid phone number'
            )
        )
        /*'commission' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter comission'
            )
        )*/
    );
}