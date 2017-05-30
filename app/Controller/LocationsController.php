<?php
/* janakiraman */
App::uses('AppController', 'Controller');

class LocationsController extends AppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Javascript');
    public $uses = array('State', 'Country', 'Location', 'City');

    /**
     * LocationsController::admin_index()
     * Location Management Proccess
     * @return void
     */
    public function admin_index($id = null) {
        if ($id != '') {
            //switch (trim($key)){
            //case 'locations';
            $location_list = $this->Location->find('all', array(
                'conditions' => array('Location.city_id' => $id,
                    'NOT' => array('Location.status' => 3))));
            // }

        } else {
            $location_list = $this->Location->find('all', array(
                'conditions' => array('NOT' => array('Location.status' => 3))));
        }

        $this->set("location_list", $location_list);
    }

    /**
     * LocationsController::admin_add()
     * Location Add  Process
     * @return void
     */
    public function admin_add()
    {

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Location->set($this->request->data);
            if($this->Location->validates()) {
                $Location = $this->Location->find('all', array(
                    'conditions' => array(
                        'area_name' => trim($this->request->data['Location']['area_name']))));
                if (!empty($Location)) {
                    $this->Session->setFlash('<p>' . __('Le lieu existe déjà', true) . '</p>', 'default',
                        array('class' => 'alert alert-danger'));
                } else {
                    $this->Location->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('Votre lieu a été enregistré', true) . '</p>', 'default',
                        array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Locations', 'action' => 'index'));
                }
            } else {
                $this->Location->validationErrors;
            }
        }

        $state_list = $this->State->find('list', array(
            'conditions' => array('State.status' => 1),
            'fields' => array('State.id', 'State.state_name')));
        
        $this->set(compact('state_list'));

    }

    /**
     * LocationsController::admin_edit()
     * Location Edit Process
     * @param mixed $id
     * @return void
     */
    public function admin_edit($id = null)
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Location->set($this->request->data);
            if($this->Location->validates()) {
                $Location = $this->Location->find('all', array(
                    'conditions' => array(
                        'area_name' => trim($this->request->data['Location']['area_name']),
                        'NOT' => array(
                            'Location.id' => $this->request->data['Location']['id']))));
                if (!empty($Location)) {
                    $this->Session->setFlash('<p>' . __('Impossible d’ajouter votre lieu', true) . '</p>', 'default',
                        array('class' => 'alert alert-danger'));
                } else {
                    $this->Location->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('Votre lieu a été enregistré', true) . '</p>', 'default',
                        array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Locations', 'action' => 'index'));
                }
            } else {
                $this->Location->validationErrors;
            }
        }
        $getStateData = $this->Location->findById($id);
        $this->set('state_list', $this->State->find('list', array(
            'conditions' => array('State.status' => 1),
            'fields' => array('id', 'state_name'))));
        $this->set('city_list', $this->City->find('list', array(
            'conditions' => array('City.status' => 1),
            'fields' => array('id', 'city_name'))));
        $this->request->data = $getStateData;
    }

    /**
     * LocationsController::admin_cityFillter()
     * City Fillter Process
     * @return void
     */
    public function admin_cityFillter()
    {
        $id = $this->request->data['id'];
        $City_list = $this->City->find('list', array(
            'conditions' => array('City.state_id' => $id,
                'NOT' => array('City.status' => 3)),
            'fields' => array('City.id', 'City.city_name')));
        $this->set(compact('City_list'));

    }

    public function admin_locationFillter()
    {
        $id = $this->request->data['id'];
        $area_list = $this->Location->find('list', array(
            'conditions' => array('Location.city_id' => $id,
                'NOT' => array('Location.status' => 3)),
            'fields' => array('Location.id', 'Location.area_name')));
        $this->set(compact('area_list'));

    }
}