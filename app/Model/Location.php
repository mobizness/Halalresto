<?php
App::uses('Model', 'Model');

class Location extends Model
{
    public $name = "Location";
    public $belongsTo = array(
        'City' => array('className' => 'City',
            'foreignKey' => 'city_id',
            'dependent' => true),
        'State' => array('className' => 'State',
            'foreignKey' => 'state_id',
            'dependent' => true));

    var $validate = array(
        'state_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Sélectionnez le departement svp'
            )
        ),
        'city_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Sélectionner la ville SVP'
            )
        ),
        'area_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the area name'
            )
        ),
        'zip_code' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the zip code name'
            ),
            'zip_no_should_be_numeric' => array(
                'rule' => 'numeric',
                'message' => 'Please enter valid zip code'
            )
        )
	);
}
