<?php
/* MN */

App::uses('Model', 'Model');

class Voucher extends Model {
    public $name = "Voucher";
    public $belongsTo = array(
			'Store' => array ('className' => 'Store',
							'foreignKey' => 'store_id',
							 'dependent' => true));
    var $validate = array(
        'store_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please select Store'
            )
        ),
        'voucher_code' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le promo code svp'
            )
        ),
        'type_offer' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter offer type'
            )
        ),
        'offer_mode' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter offer mode'
            )
        ),
        'from_date' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please select from date'
            )
        ),
        'to_date' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please select to date'
            )
        )
    );
}