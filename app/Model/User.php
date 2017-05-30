<?php

App::uses('Model', 'Model');

class User extends AppModel
{
    var $name = 'User';
    public $hasOne = array(
        'Customer' => array('className' => 'Customer',
                            'foreignKey' => 'user_id',
                            'dependent' => true),
        'Store' => array('className' => 'Store',
                            'foreignKey' => 'user_id',
                            'dependent' => true),
        'Driver' => array('className' => 'Driver',
                            'foreignKey' => 'parent_id',
                            'dependent' => true));
    var $validate = array(
        'username' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Entrez votre nom utilisateur svp'),
        'password' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Entrez le mot de passe')
    );
}