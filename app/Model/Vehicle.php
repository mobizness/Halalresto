<?php

App::uses('Model', 'Model');

class Vehicle extends AppModel
{
    var $validate = array(
        'vehicle_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le nom du véhicule svp'
            )
        ),
        'model_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le svp modèle de véhicule'
            ),
        ),
        'color' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le svp couleur de véhicule'
            )
        ),
        'year' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Entrez le svp année'
            ),
            'phone_no_should_be_numeric' => array(
                'rule' => 'numeric',
                'message' => 'Please enter valid year'
            )
        ),
        'vehicle_no' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please choose the gender'
            )
        )
    );
}