<?php
/* janakiraman */
App::uses('AppController', 'Controller');

class StatesController extends AppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Javascript');
    public $uses = array('Country', 'State', 'City');

    /**
     * StatesController::admin_index()
     * State Management Detail
     * @return void
     */
    public function admin_index()
    {
        if (isset($this->request->params['pass'][0])) {
            $state_list = $this->State->find('all', array(
                'conditions' => array('State.country_id' => $this->request->params['pass'][0],
                    'NOT' => array('State.status' => 3))));
        } else {
            $state_list = $this->State->find('all', array(
                'conditions' => array('NOT' => array('State.status' => 3))));
        }

        $this->set("state_list", $state_list);
    }

    /**
     * StatesController::admin_add()
     * State Add Detail
     * @return void
     */
    public function admin_add()
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->State->set($this->request->data);
            if($this->State->validates()) {
                $State = $this->State->find('first', array(
                    'conditions' => array('state_name' => trim($this->request->data['State']['state_name']))));
                if (!empty($State)) {
                    $this->Session->setFlash('<p>' . __('Le département existe déjà', true) . '</p>', 'default',
                        array('class' => 'alert alert-danger'));
                } else {
                    $this->State->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('Votre département a été enregistré', true) . '</p>', 'default',
                        array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'states', 'action' => 'index'));
                }
            } else {
                $this->State->validationErrors;
            }
        }
        $country_list = $this->Country->find('list', array(
            'conditions' => array('Country.status' => 1),
            'fields' => array('Country.id', 'Country.country_name')));

        $this->set("country_list", $country_list);
    }

    /**
     * StatesController::admin_edit()
     * State Edit Detail
     * @param mixed $id
     * @return void
     */
    public function admin_edit($id = null)
    {

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->State->set($this->request->data);
            if($this->State->validates()) {
                $State = $this->State->find('first', array(
                    'conditions' => array(
                        'State.state_name' => $this->request->data['State']['state_name'],
                        'State.country_id' => $this->request->data['State']['country_id'],
                        'NOT' => array('State.id' => $this->request->data['State']['id']))));
                if (!empty($State)) {
                    $this->Session->setFlash('<p>' . __('Le département existe déjà', true) . '</p>', 'default',
                        array('class' => 'alert alert-danger'));
                } else {
                    $this->State->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('Votre département a été enregistré', true) . '</p>', 'default',
                        array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'states', 'action' => 'index'));
                }
            } else {
                $this->State->validationErrors;
            }
        }
        $getStateData = $this->State->findById($id);
        $this->set('country_list', $this->Country->find('list', array(
            'conditions' => array('Country.status' => 1),
            'fields' => array('id', 'country_name'))));
        $this->request->data = $getStateData;
    }

    /**
     * StatesController::admin_citylist()
     * State Based Filleter Process
     * @param mixed $id
     * @return void
     */

    public function admin_stateList($id)
    {
        if ($id == 0) {
            $state_detail = $this->State->find('all');
        } else {
            $state_detail = $this->State->find('all', array(
                'conditions' => array('City.state_id' => $id)));
        }
        $this->set("state_detail", $state_detail);
    }

    public function admin_cityList($id)
    {
        if ($id == 0) {
            $city_detail = $this->City->find('all');
        } else {
            $city_detail = $this->City->find('all', array(
                'conditions' => array('City.state_id' => $id)));
        }
        $this->set("city_detail", $city_detail);
    }
    /*  public function admin_stateChecking(){
            $id = $this->request->data([id]);
            $state_detail = $this->State->find('list',array(
                'conditions'=>array('State.country_id'=>$id,
                    'NOT'=>array('State.status'=>3)),
                'fields'=>array('State.id','State.state_name')));
            $this->set(compact('state_detail'));
        }*/
}