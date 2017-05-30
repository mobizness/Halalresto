<?php

/* MN */
App::uses('AppController', 'Controller');

class VouchersController extends AppController {

    public $helpers = array('Html', 'Form', 'Session', 'Javascript');
    public $uses = array('Voucher', 'Store');

    /**
     * VouchersController::admin_index()
     * Display detail of voucher management
     * @return void
     */
    public function admin_index() {
        $Voucher_list = $this->Voucher->find('all', array(
            'conditions' => array('NOT' => array('Voucher.status' => 3)),
            'order' => array('Voucher.id DESC')));
        $this->set('Voucher_list', $Voucher_list);
    }

    /**
     * VouchersController::admin_add()
     * Add Vocher Detail
     * @return void
     */
    public function admin_add() {
        if (!empty($this->request->data['Voucher'])) {
            $this->Voucher->set($this->request->data);
            if ($this->Voucher->validates()) {
                $voucher = $this->Voucher->find('all', array(
                    'conditions' => array('Voucher.store_id' => $this->request->data['Voucher']['store_id'],
                        'voucher_code' => trim($this->request->data['Voucher']['voucher_code']))));
                if (!empty($voucher)) {
                    $this->Session->setFlash('<p>' . __('Le code voucher existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->request->data['Voucher']['offer_value'] = ($this->request->data['Voucher']['offer_mode'] != 'free_delivery') ?
                            $this->request->data['Voucher']['offer_value'] : 0;
                    $this->Voucher->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('Votre voucher a été enregistré', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Vouchers', 'action' => 'index'));
                }
            } else {
                $this->Voucher->validationErrors;
            }
        }

        $stores = $this->Store->find('list', array('conditions' => array('Store.status' => 1),
            'fields' => array('id', 'store_name')));

        $this->set(compact('stores'));
    }

    /**
     * VouchersController::admin_edit()
     * Edit Vocher Detail
     * @param mixed $id
     * @return void
     */
    public function admin_edit($id = null) {
        if (!empty($this->request->data['Voucher'])) {
            $this->Voucher->set($this->request->data);
            if ($this->Voucher->validates()) {
                $voucher = $this->Voucher->find('all', array(
                    'conditions' => array(
                        'Voucher.store_id' => $this->request->data['Voucher']['store_id'],
                        'Voucher.voucher_code' => trim($this->request->data['Voucher']['voucher_code']),
                        'NOT' => array('Voucher.id' => trim($this->request->data['Voucher']['id'])))));
                if (!empty($voucher)) {
                    $this->Session->setFlash('<p>' . __('Le code voucher existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {

                    $this->request->data['Voucher']['offer_value'] = ($this->request->data['Voucher']['offer_mode'] != 'free_delivery') ?
                            $this->request->data['Voucher']['offer_value'] : 0;

                    $this->Voucher->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('Votre voucher a été enregistré', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Vouchers', 'action' => 'index'));
                }
            } else {
                $this->Voucher->validationErrors;
            }
        }
        $this->request->data = $this->Voucher->findById($id);
        $stores = $this->Store->find('list', array('conditions' => array('Store.status' => 1),
            'fields' => array('id', 'store_name')));

        $this->set(compact('stores'));
    }

    /**
     * VouchersController::store_index()
     * Display detail of voucher management
     * @return void
     */
    public function store_index() {
        $this->layout = 'assets';
        $Voucher_list = $this->Voucher->find('all', array(
            'conditions' => array('Voucher.store_id' => $this->Auth->User('Store.id'),
                'NOT' => array('Voucher.status' => 3)),
            'order' => array('Voucher.id DESC')));
        $this->set('Voucher_list', $Voucher_list);
    }

    /**
     * VouchersController::store_add()
     * Add Vocher Detail
     * @return void
     */
    public function store_add() {
        $this->layout = 'assets';
        if (!empty($this->request->data['Voucher'])) {
            $this->Voucher->set($this->request->data);
            if ($this->Voucher->validates()) {
                $voucher = $this->Voucher->find('all', array(
                    'conditions' => array('Voucher.store_id' => $this->Auth->User('Store.id'),
                        'voucher_code' => trim($this->request->data['Voucher']['voucher_code']))));
                if (!empty($voucher)) {
                    $this->Session->setFlash('<p>' . __('Le code voucher existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->request->data['Voucher']['offer_value'] = ($this->request->data['Voucher']['offer_mode'] != 'free_delivery') ?
                            $this->request->data['Voucher']['offer_value'] : 0;
                    $this->request->data['Voucher']['store_id'] = $this->Auth->User('Store.id');
                    $this->Voucher->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('Votre voucher a été enregistré', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Deals', 'action' => 'index'));
                }
            } else {
                $this->Voucher->validationErrors;
            }
        }
    }

    /**
     * VouchersController::store_edit()
     * Edit Vocher Detail
     * @param mixed $id
     * @return void
     */
    public function store_edit($id = null) {
        $this->layout = 'assets';
        if (!empty($this->request->data['Voucher'])) {
            $this->Voucher->set($this->request->data);
            if ($this->Voucher->validates()) {
                $getVoucherData = $this->Voucher->find('first', array(
                    'conditions' => array('Voucher.id' => $this->request->data['Voucher']['id'],
                        'Voucher.store_id' => $this->Auth->User('Store.id'),
                        'NOT' => array('Voucher.status' => $this->request->data['Voucher']['id'],
                            'Voucher.status' => 3,))));
                if (empty($getVoucherData)) {
                    $this->render('/Errors/error400');
                }

                $voucher = $this->Voucher->find('all', array(
                    'conditions' => array('Voucher.store_id' => $this->Auth->User('Store.id'),
                        'Voucher.voucher_code' => trim($this->request->data['Voucher']['voucher_code']),
                        'NOT' => array('Voucher.id' => trim($this->request->data['Voucher']['id'])))
                ));
                if (!empty($voucher)) {
                    $this->Session->setFlash('<p>' . __('Le code voucher existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->request->data['Voucher']['offer_value'] = ($this->request->data['Voucher']['offer_mode'] != 'free_delivery') ?
                            $this->request->data['Voucher']['offer_value'] : 0;
                    $this->Voucher->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('Votre voucher a été enregistré', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Vouchers', 'action' => 'index'));
                }
            } else {
                $this->Voucher->validationErrors;
            }
        }

        $getVoucherData = $this->Voucher->find('first', array(
            'conditions' => array('Voucher.id' => $id,
                'Voucher.store_id' => $this->Auth->User('Store.id'))));
        if (empty($getVoucherData)) {
            $this->render('/Errors/error400');
        }
        $this->request->data = $getVoucherData;
    }

}
