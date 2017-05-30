<?php

/* MN */

App::uses('Model', 'Model');


class DeliveryLocation extends AppModel
{
    
    public $belongsTo = array(
        'Store' => [
            'className' => 'Store',
            'foreignKey' => 'store_id',
            'dependent' => true
        ],
        'City' => [
            'className' => 'City',
            'foreignKey' => 'city_id',
            'dependent' => true
        ],
        'Location' => [
            'className' => 'Location',
            'foreignKey' => 'location_id',
            'dependent' => true
        ]
    );
}