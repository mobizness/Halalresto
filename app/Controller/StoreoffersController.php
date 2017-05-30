<?php

/* janakiraman */
App::uses('AppController', 'Controller');

class StoreoffersController extends AppController {

    public $helpers = array('Html', 'Form', 'Session', 'Javascript');
    public $uses = array('Storeoffer', 'Store');

    /**
     * VouchersController::admin_index()
     * Storeoffers management detail
     * @return void
     */
    public function admin_index($storeId = null) {

        if ($storeId != '') {
            $Storeoffer_list = $this->Storeoffer->find('all', array(
                'conditions' => array('Storeoffer.store_id' => $storeId,
                    'NOT' => array('Storeoffer.status' => 3)),
                'order' => 'Storeoffer.id DESC'));
        } else {
            $Storeoffer_list = $this->Storeoffer->find('all', array(
                'conditions' => array('NOT' => array('Storeoffer.status' => 3))));
        }
        $this->set('Storeoffer_list', $Storeoffer_list);
    }

    /**
     * StoreoffersController::admin_add()
     * Storeoffers Add detail
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Storeoffer->set($this->request->data);
            if ($this->Storeoffer->validates()) {

                $Storeoffer = $this->Storeoffer->find('all', array(
                    'conditions' => array('Storeoffer.status !=' => 3,
                        'Storeoffer.offer_price' => trim($this->request->data['Storeoffer']['offer_price']),
                        'Storeoffer.store_id' => trim($this->request->data['Storeoffer']['store_id']))
                ));
                if (!empty($Storeoffer)) {
                    $this->Session->setFlash('<p>' . __('L’offre de prix du restaurant existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->Storeoffer->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('L’offre du restaurant a été enregistrée', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Storeoffers', 'action' => 'index'));
                }
            } else {
                $this->Storeoffer->validationErrors;
            }
        }
        $Store_list = $this->Store->find('list', array(
            'fields' => array('Store.id', 'Store.store_name'),
            'conditions' => array('Store.status' => 1)));
        $this->set('Store_list', $Store_list);
    }

    /**
     * StoreoffersController::admin_edit()
     * Storeoffers edit detail
     * @return void
     */
    public function admin_edit($id = null) {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Storeoffer->set($this->request->data);
            if ($this->Storeoffer->validates()) {
                $Storeoffer = $this->Storeoffer->find('all', array(
                    'conditions' => array(
                        'Storeoffer.offer_price' =>
                        trim($this->request->data['Storeoffer']['offer_price']),
                        'Storeoffer.store_id' => $this->request->data['Storeoffer']['store_id'],
                        'NOT' => array('Storeoffer.id' =>
                            trim($this->request->data['Storeoffer']['id']),
                            'Storeoffer.status' => 3))
                ));
                if (!empty($Storeoffer)) {
                    $this->Session->setFlash('<p>' . __('L’offre de prix du restaurant existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->Storeoffer->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('L’offre du restaurant a été enregistrée', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Storeoffers', 'action' => 'index'));
                }
            } else {
                $this->Storeoffer->validationErrors;
            }
        }
        $Store_list = $this->Store->find('list', array(
            'conditions' => array('Store.status' => 1),
            'fields' => array(
                'Store.id', 'Store.store_name')));
        $this->set('Store_list', $Store_list);
        $getStateData = $this->Storeoffer->findById($id);
        $this->request->data = $getStateData;
    }

    public function store_index() {
        $this->layout = 'assets';
        $Storeoffer_list = $this->Storeoffer->find('all', array(
            'conditions' => array('Storeoffer.store_id' => $this->Auth->User('Store.id'),
                'NOT' => array('Storeoffer.status' => 3)),
            'order' => 'Storeoffer.id DESC')
        );
        $this->set('Storeoffer_list', $Storeoffer_list);
    }

    public function store_add() {
        $this->layout = 'assets';
        $id = $this->Auth->User();
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Storeoffer->set($this->request->data);
            if ($this->Storeoffer->validates()) {
                $Storeoffer = $this->Storeoffer->find('all', array(
                    'conditions' => array('Storeoffer.status !=' => 3,
                        'Storeoffer.offer_price' =>
                        trim($this->request->data['Storeoffer']['offer_price']),
                        'AND' => array('Storeoffer.store_id' => trim($id['Store']['id'])))
                ));
                if (!empty($Storeoffer)) {
                    $this->Session->setFlash('<p>' . __('L’offre de prix du restaurant existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->request->data['Storeoffer']['store_id'] = $id['Store']['id'];
                    $this->Storeoffer->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('L’offre du restaurant a été enregistrée', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Deals', 'action' => 'index'));
                }
            } else {
                $this->Storeoffer->validationErrors;
            }
        }
    }

    public function store_edit($id = null) {
        $this->layout = 'assets';
        $ids = $this->Auth->User();
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Storeoffer->set($this->request->data);
            if ($this->Storeoffer->validates()) {

                $getStoreofferData = $this->Storeoffer->find('first', array(
                    'conditions' => array('Storeoffer.id' => $this->request->data['Storeoffer']['id'],
                        'Storeoffer.store_id' => $this->Auth->User('Store.id'),
                        'NOT' => array('Storeoffer.status' => $this->request->data['Storeoffer']['id'],
                            'Storeoffer.status' => 3,))));

                if (empty($getStoreofferData)) {
                    $this->render('/Errors/error400');
                }

                $Storeoffer = $this->Storeoffer->find('first', array(
                    'conditions' => array(
                        'Storeoffer.offer_price' =>
                        trim($this->request->data['Storeoffer']['offer_price']),
                        'Storeoffer.store_id' => $this->Auth->User('Store.id'),
                        'NOT' => array('Storeoffer.id' =>
                            trim($this->request->data['Storeoffer']['id'])))
                ));
                if (!empty($Storeoffer)) {
                    $this->Session->setFlash('<p>' . __('L’offre de prix du restaurant existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->Storeoffer->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('L’offre du restaurant a été enregistrée', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Storeoffers', 'action' => 'index'));
                }
            } else {
                $this->Storeoffer->validationErrors;
            }
        }

        $getStoreofferData = $this->Storeoffer->find('first', array(
            'conditions' => array('Storeoffer.id' => $id,
                'Storeoffer.store_id' => $this->Auth->User('Store.id'))));
        if (empty($getStoreofferData)) {
            $this->render('/Errors/error400');
        }
        $this->request->data = $getStoreofferData;
    }

}
