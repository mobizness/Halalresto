<?php
/* janakiraman */
App::uses('AppController', 'Controller');

class CitiesController extends AppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Javascript');
    public $uses = array('State', 'Country', 'City', 'Location');

    /**
     * CitiesController::admin_index()
     * City Management Process
     * @return void
     */
    public function admin_index($id = null)
    {
        if (isset($id)) {
            $city_list = $this->City->find('all', array(
                'conditions' => array('City.state_id' => $id,
                    'NOT' => array('City.status' => 3))));
        } else {
            $city_list = $this->City->find('all', array(
                'conditions' => array(
                    'NOT' => array('City.status' => 3))));
        }

        $this->set("city_list", $city_list);
    }

    /**
     * CitiesController::admin_add()
     * City Add Process
     * @return void
     */
    public function admin_add()
    {

        if ($this->request->is('post')) {
            $this->City->set($this->request->data);
            if($this->City->validates()) {
                $City = $this->City->find('all', array(
                        'conditions' => array(
                            ' city_name' => trim($this->request->data['City']['city_name'])))
                );
                if (!empty($City)) {
                    $this->Session->setFlash('<p>' . __('Le nom de la ville existe déjà', true) . '</p>', 'default',
                        array('class' => 'alert alert-danger'));
                } else {
                    $this->City->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('Votre ville a été enregistrée', true) . '</p>', 'default',
                        array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Cities', 'action' => 'index'));
                }
            } else {
                $this->City->validationErrors;
            }
        }
        $country_list = $this->Country->find('list', array(
            'conditions' => array('Country.status' => 1),
            'fields' => array(
                'Country.id', 'Country.country_name')));
        $this->set("country_list", $country_list);
    }

    /**
     * CitiesController::admin_edit()
     * City Edit Process
     * @param mixed $id
     * @return void
     */
    public function admin_edit($id = null)
    {

         if ($this->request->is('post') || $this->request->is('put')) {
            $this->City->set($this->request->data);
            if($this->City->validates()) {
                $City_list = $this->City->find('first', array(
                    'conditions' => array(
                        'City.city_name' => $this->request->data['City']['city_name'],
                        'City.state_id' => $this->request->data['City']['state_id'],
                        'NOT' => array('City.id' => $this->request->data['City']['id']))));

                if (!empty($City_list)) {
                    $this->Session->setFlash('<p>' . __('Le nom de la ville existe déjà', true) . '</p>', 'default',
                        array('class' => 'alert alert-danger'));
                } else {
                    $this->City->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('Votre ville a été mise à jour', true) . '</p>', 'default',
                        array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Cities', 'action' => 'index'));
                }
            } else {
                $this->City->validationErrors;
            }
        }
        $getStateData = $this->City->findById($id);
        $this->set('state_list', $this->State->find('list', array(
                'conditions' => array('State.status' => 1),
                'fields' => array('id', 'state_name'))
        ));
        $this->set('country_list', $this->Country->find('list', array(
            'conditions' => array('Country.status' => 1),
            'fields' => array('Country.id', 'Country.country_name')))
        );
        $this->request->data = $getStateData;
    }

    /**
     * CitiesController::admin_arealist()
     * Area Based Fillter
     * @param mixed $id
     * @return void
     */
    public function admin_areaList($id = null)
    {
        if ($id != 0) {
            $city_detail = $this->Location->find('all', array(
                'conditions' => array('Location.city_id' => $id),
                'fields' => array('Location.id', 'Location.zip_code', 'Location.area_name', 'Location.status')));
        } else {
            $city_detail = $this->Location->find('all');
        }
        $this->set("city_detail", $city_detail);
    }

    /**
     * CitiesController::admin_stateFillter()
     * State Fillter Process
     * @return void
     */
    public function admin_stateFillter()
    {
        $id = $this->request->data['id'];
        $State_list = $this->State->find('list', array(
            'conditions' => array('State.country_id' => $id,
                'NOT' => array('State.status' => 3)),
            'fields' => array('State.id', 'State.state_name')));
        $this->set(compact('State_list'));

    }
}