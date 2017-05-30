<?php

/* janakiraman */
App::uses('AppController', 'Controller');

class AddonsController extends AppController {

    public $helpers = array('Html', 'Form', 'Session', 'Javascript');
    public $uses = array('Mainaddon', 'Subaddon', 'Store', 'Category');

    /**
     * AddonsController::admin_index()
     * Categories Management Process
     * @return void
     */
    public function admin_index($storeId = null) {
        $AddonsList = $this->Mainaddon->find('all', array(
            'conditions' => array(
                'Mainaddon.store_id' => $storeId,
                'NOT' => array(
                    'Mainaddon.status' => 3
                )
            ),
            'order' => array(
                'Mainaddon.id DESC'
            )
                )
        );

        $stores = $this->Store->find('list', array(
            'fields' => array(
                'Store.id',
                'Store.store_name'
            ),
            'conditions' => array(
                'Store.status' => 1
            )
                )
        );
        $this->set(compact('AddonsList', 'stores', 'storeId'));
    }

    /**
     * AddonsController::admin_add()
     * Addons Add Process
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            if ($this->Mainaddon->validates()) {
                $mainAddons = $this->request->data['Mainaddon'];
                if ($this->Mainaddon->save($mainAddons, null, null)) {
                    if (!empty($mainAddons['Subaddon'])) {
                        foreach ($mainAddons['Subaddon'] as $key => $value) {
                            $storeId = $this->request->data['Mainaddon']['store_id'];
                            if (!empty($value['subaddons_name'])) {
                                $value['mainaddons_id'] = $this->Mainaddon->id;
                                $value['store_id'] = $storeId;
                                $value['category_id'] = $this->request->data['Mainaddon']['category_id'];
                                if (empty($value['subaddons_price'])) {
                                    $value['subaddons_price'] = '0.00';
                                }
                                $this->Subaddon->save($value, null, null);
                                $this->Subaddon->id = '';
                            }
                        }
                    }
                }

                $this->Session->setFlash('<p>' . __('Vos articles ont été enregistrés', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('controller' => 'Addons', 'action' => 'index', $storeId));
            } else {
                $this->Mainaddon->validationErrors;
            }
        }

        $category_list = $this->Category->find('list', array(
            'fields' => array(
                'Category.id',
                'Category.category_name'
            ),
            'conditions' => array(
                'Category.parent_id' => 0,
                'Category.status' => 1
            )
                )
        );

        $stores = $this->Store->find('list', array(
            'fields' => array(
                'Store.id',
                'Store.store_name'
            ),
            'conditions' => array(
                'Store.status' => 1
            )
                )
        );
        $this->set(compact('category_list', 'stores'));
    }

    /**
     * AddonsController::admin_edit()
     * Addons Edit Process
     * @return void
     */
    public function admin_edit($id = null) {

        if ($this->request->is('post')) {
            if ($this->Mainaddon->validates()) {
                $mainAddons = $this->request->data['Mainaddon'];
                if ($this->Mainaddon->save($mainAddons, null, null)) {

                    $this->Subaddon->deleteAll(array(
                        'Subaddon.mainaddons_id' => $this->Mainaddon->id
                    ));
                    if (!empty($mainAddons['Subaddon'])) {
                        foreach ($mainAddons['Subaddon'] as $key => $value) {
                            $storeId = $this->request->data['Mainaddon']['store_id'];
                            if (!empty($value['subaddons_name'])) {
                                $value['mainaddons_id'] = $this->Mainaddon->id;
                                $value['store_id'] = $storeId;
                                $value['category_id'] = $this->request->data['Mainaddon']['category_id'];
                                if (empty($value['subaddons_price'])) {
                                    $value['subaddons_price'] = '0.00';
                                }
                                $this->Subaddon->save($value, null, null);
                                $this->Subaddon->id = '';
                            }
                        }
                    }
                }
                $this->Session->setFlash('<p>' . __('Vos articles ont été enregistrés', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('controller' => 'Addons', 'action' => 'index', $storeId));
            } else {
                $this->Mainaddon->validationErrors;
            }
        }

        $category_list = $this->Category->find('list', array(
            'fields' => array(
                'Category.id',
                'Category.category_name'
            ),
            'conditions' => array(
                'Category.parent_id' => 0,
                'Category.status' => 1
            )
                )
        );

        $stores = $this->Store->find('list', array(
            'fields' => array(
                'Store.id',
                'Store.store_name'
            ),
            'conditions' => array(
                'Store.status' => 1
            )
                )
        );
        $addonsDetail = $this->Mainaddon->findById($id);
        $this->request->data = $addonsDetail;
        $this->set(compact('category_list', 'stores'));
    }

    /**
     * AddonsController::store_index()
     * Addons Management Process
     * @return void
     */
    public function store_index() {
        $this->layout = 'assets';
        $stores_id = $this->Auth->User();
        $store_id = $stores_id['Store']['id'];
        $AddonsList = $this->Mainaddon->find('all', array(
            'conditions' => array(
                'store_id' => $store_id,
                'NOT' => array(
                    'Mainaddon.status' => 3
                )
            ),
            'order' => array(
                'Mainaddon.id DESC'
            )
                )
        );
        $this->set('AddonsList', $AddonsList);
    }

    /**
     * AddonsController::store_add()
     * Addons Add Process
     * @return void
     */
    public function store_add() {
        $this->layout = 'assets';
        $stores_id = $this->Auth->User();
        $store_id = $stores_id['Store']['id'];
        if ($this->request->is('post')) {
            if ($this->Mainaddon->validates()) {
                $mainAddons = $this->request->data['Mainaddon'];
                if ($this->Mainaddon->save($mainAddons, null, null)) {
                    if (!empty($mainAddons['Subaddon'])) {
                        foreach ($mainAddons['Subaddon'] as $key => $value) {
                            if (!empty($value['subaddons_name'])) {
                                $value['mainaddons_id'] = $this->Mainaddon->id;
                                $value['store_id'] = $this->request->data['Mainaddon']['store_id'];
                                $value['category_id'] = $this->request->data['Mainaddon']['category_id'];
                                if (empty($value['subaddons_price'])) {
                                    $value['subaddons_price'] = '0.00';
                                }
                                $this->Subaddon->save($value, null, null);
                                $this->Subaddon->id = '';
                            }
                        }
                    }
                }

                $this->Session->setFlash('<p>' . __('Vos articles ont été enregistrés', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                //$this->redirect(array('controller' => 'Addons', 'action' => 'index'));
                $this->redirect(array('controller' => 'Categories', 'action' => 'index'));
            } else {
                $this->Mainaddon->validationErrors;
            }
        }

        $category_list = $this->Category->find('list', array(
            'fields' => array(
                'Category.id',
                'Category.category_name'
            ),
            'conditions' => array(
                'Category.parent_id' => 0,
                'Category.status' => 1,
                'Category.store_id' => $store_id
            )
                )
        );

        $this->set(compact('category_list', 'stores', 'store_id'));
    }

    /**
     * AddonsController::store_edit()
     * Addons Edit Process
     * @return void
     */
    public function store_edit($id = null) {
        $this->layout = 'assets';
        $stores_id = $this->Auth->User();
        $store_id = $stores_id['Store']['id'];
        if ($this->request->is('post')) {
            if ($this->Mainaddon->validates()) {
                $mainAddons = $this->request->data['Mainaddon'];
                if ($this->Mainaddon->save($mainAddons, null, null)) {

                    $this->Subaddon->deleteAll(array(
                        'Subaddon.mainaddons_id' => $this->Mainaddon->id
                    ));
                    if (!empty($mainAddons['Subaddon'])) {
                        foreach ($mainAddons['Subaddon'] as $key => $value) {
                            if (!empty($value['subaddons_name'])) {
                                $value['mainaddons_id'] = $this->Mainaddon->id;
                                $value['store_id'] = $this->request->data['Mainaddon']['store_id'];
                                $value['category_id'] = $this->request->data['Mainaddon']['category_id'];
                                if (empty($value['subaddons_price'])) {
                                    $value['subaddons_price'] = '0.00';
                                }
                                $this->Subaddon->save($value, null, null);
                                $this->Subaddon->id = '';
                            }
                        }
                    }
                }
                $this->Session->setFlash('<p>' . __('Vos articles ont été enregistrés', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                //$this->redirect(array('controller' => 'Addons', 'action' => 'index'));
                $this->redirect(array('controller' => 'Categories', 'action' => 'index'));
            } else {
                $this->Mainaddon->validationErrors;
            }
        }

        $category_list = $this->Category->find('list', array(
            'fields' => array(
                'Category.id',
                'Category.category_name'
            ),
            'conditions' => array(
                'Category.parent_id' => 0,
                'Category.status' => 1
            )
                )
        );

        $addonsDetail = $this->Mainaddon->findById($id);
        $this->request->data = $addonsDetail;
        $this->set(compact('category_list', 'stores', 'store_id'));
    }

    public function removeSubAddons() {
        $subaddonId = $this->request->data['id'];
        if (!empty($subaddonId)) {
            $this->Subaddon->deleteAll(array(
                'Subaddon.id' => $subaddonId
            ));
            echo 'Success';
        }
        die();
    }

    public function checkAddonExist() {

        $mainAddonid = $this->request->data['mainAddonid'];
        $mainAddonName = $this->request->data['mainAddonName'];
        $storeId = $this->request->data['storeId'];
        $categoryId = $this->request->data['categoryId'];

        if (!empty($mainAddonid)) {
            $condition = array(
                'Mainaddon.mainaddons_name' => $mainAddonName,
                'Mainaddon.store_id' => $storeId,
                'Mainaddon.category_id' => $categoryId,
                'NOT' => array(
                    'Mainaddon.id' => $mainAddonid,
                    'Store.status' => 3
                )
            );
        } else {
            $condition = array(
                'Mainaddon.mainaddons_name' => $mainAddonName,
                'Mainaddon.store_id' => $storeId,
                'Mainaddon.category_id' => $categoryId,
                'NOT' => array(
                    'Store.status' => 3
                )
            );
        }

        $AddonsCheck = $this->Mainaddon->find('first', array(
            'conditions' => $condition
                )
        );

        if (!empty($AddonsCheck)) {
            echo 'Exist';
        } else {
            echo 'Available';
        }
        die();
    }

}
