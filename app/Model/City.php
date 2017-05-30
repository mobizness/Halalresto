<?php
App::uses('Model', 'Model');

class City extends Model
{
    public $name = "City";
    public $belongsTo = array(
        'State' => array('className' => 'State',
            'foreignKey' => 'state_id',
            'dependent' => true),
        'Country' => array('className' => 'Country',
            'foreignKey' => 'country_id',
            'dependent' => true),
    );

    var $validate = array(
        'country_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Sélectionnez le pays svp'
            )
        ),
        'state_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Sélectionnez le departement svp'
            )
        ),
        'city_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le nom de la ville svp'
            )
        )
	);
}
