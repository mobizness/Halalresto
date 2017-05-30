<?php

App::uses('Model', 'Model');

class Storeoffer extends Model {    
    public $name   = "Storeoffer";   
    public $belongsTo = array(
			'Store' => array ('className' => 'Store',
							'foreignKey' => 'store_id',
							 'dependent' => true));

    var $validate = array(
        'store_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Sélectionnez le restaurant svp'
            )
        ),
        'offer_percentage' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le % de réduction svp'
            ),
            'offerPercentage_no_should_be_numeric' => array(
                'rule' => 'numeric',
                'message' => 'Entrez le % de réduction svp'
            )
        ),
        'offer_price' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Veuillez entrer le prix de vente'
            ),
            'offerPrice_no_should_be_numeric' => array(
                'rule' => 'numeric',
                'message' => 'Please enter valid offer price'
            )
        ),
        'from_date' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez la date de début svp'
            )
        ),
        'to_date' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez la date de fin svp'
            )
        ),
    );
}