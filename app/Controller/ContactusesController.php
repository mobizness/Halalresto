<?php

/* janakiraman */
App::uses('AppController','Controller');
App::uses('CakeEmail', 'Network/Email');

class ContactusesController extends AppController {
    public $helpers = array('Html','Form', 'Session', 'Javascript');
    public $uses    = array('ContactUs');
    /**
    * NewslettersController::admin_index()
    * Newsletter Management
    * @return void
    */
    public function beforeFilter() {
        $this->Auth->allow(array('ContactUs'));
        parent::beforeFilter();
    }

    public function admin_index() {
        $contactList = $this->ContactUs->find('all', array(
            'conditions' => array(
                'ContactUs.status !=' => 3
            )
        ));
        $this->set('contactList',$contactList);
    }

    /**
    * UsersController::contactUs()
    * Contact Us
    * @return void
    */
    public function contactUs() {
        $this->layout = 'frontend';

        if ($this->request->is('post') || $this->request->is('put')) {
            //echo "<pre>"; print_r($this->request->data); die();
            $this->ContactUs->save($this->request->data);
            $this->Session->setFlash('<p>'.__('Your contactUs has been saved', true).'</p>', 'default',
              array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'contactuses', 'action' => 'contactUs'));
        }
    }

}