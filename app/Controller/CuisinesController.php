<?php
/* janakiraman */
App::uses('AppController','Controller');
class CuisinesController extends AppController {
    public $helpers = array('Html','Form', 'Session', 'Javascript');
    public $uses    = array('Cuisine');
    /**
    * CuisinesController::admin_index()
    * Brand Management  Process
    * @return void
    */
    public function admin_index() {

        $cuisine_list = $this->Cuisine->find('all',array(
                    'conditions'=>array('NOT'=>array('Cuisine.status'=>3)),
                    'order'=>array('Cuisine.id DESC')));
        $this->set('cuisine_list',$cuisine_list);
    }
  /**
   * CuisinesController::admin_add()
   * Brand Add Process
   * @return void
   */
    public function admin_add(){
        if($this->request->is('post')) {

            $cuisine_check = $this->Cuisine->find('all',array(
                                'conditions'=>array(
                                    'cuisine_name' => trim($this->request->data['Cuisine']['cuisine_name'])
                                )));
            if(!empty($cuisine_check)) {
                $this->Session->setFlash('<p>'.__('Spécialité culinaire déjà existante', true).'</p>', 'default',
                                              array('class' => 'alert alert-danger'));
            } else {
                $this->Cuisine->save($this->request->data,null,null);
                $this->Session->setFlash('<p>'.__('Votre spécialité culinaire a été enregistrée', true).'</p>', 'default',
                                              array('class' => 'alert alert-success'));
                $this->redirect(array('controller' => 'Cuisines','action' => 'index'));
            }
        }
    }

    /**
    * CuisinesController::admin_edit()
    * Brand Edit Process
    * @param mixed $id
    * @return void
    */
    public function admin_edit($id = null) {
        if(!empty($this->request->data['Cuisine']['cuisine_name'])) {
            $Cuisine = $this->Cuisine->find('all',array(
                           'conditions'=>array('cuisine_name'=>trim($this->request->data['Cuisine']['cuisine_name']),
                            'NOT' => array('Cuisine.id' => $this->request->data['Cuisine']['id'])
                          )));
            if(!empty($Cuisine)) {
                $this->Session->setFlash('<p>'.__('Spécialité culinaire déjà existante', true).'</p>', 'default',
                                                  array('class' => 'alert alert-danger'));
            } else {
                $this->Cuisine->save($this->request->data,null,null);
                $this->Session->setFlash('<p>'.__('Votre spécialité culinaire a été enregistrée', true).'</p>', 'default',
                                                  array('class' => 'alert alert-success'));
                $this->redirect(array('controller' => 'Cuisines','action' => 'index'));
            }
        }
        $getStateData        = $this->Cuisine->findById($id);
        $this->request->data = $getStateData;
    }
    public function store_index() {
        $this->layout  = 'assets';
        $id            = $this->Auth->User();
        $cuisine_list   = $this->Cuisine->find('all',array(
                        'conditions'=>array(
                        'NOT'=>array('Cuisine.status'=>3)),
                        'order'=>array('Cuisine.id DESC')));
        $this->set('cuisine_list',$cuisine_list);
    }
    public function store_add() {
        $this->layout  = 'assets';
        if($this->request->is('post')) {

            $cuisine_check = $this->Cuisine->find('all',array(
                                'conditions'=>array(
                                    'cuisine_name' => trim($this->request->data['Cuisine']['cuisine_name'])
                                )));
            if(!empty($cuisine_check)) {
                $this->Session->setFlash('<p>'.__('Impossible d’ajouter votre spécialité culinaire', true).'</p>', 'default',
                                              array('class' => 'alert alert-danger'));
            } else {
                $this->Cuisine->save($this->request->data,null,null);
                $this->Session->setFlash('<p>'.__('Votre spécialité culinaire a été enregistrée', true).'</p>', 'default',
                                                  array('class' => 'alert alert-success'));
                $this->redirect(array('controller' => 'Cuisines','action' => 'index'));
            }
        }
    }

    public function store_edit($id = null) {
        $this->layout  = 'assets';
        if(!empty($this->request->data['Cuisine']['cuisine_name'])) {
            $Cuisine = $this->Cuisine->find('all',array(
                           'conditions'=>array('cuisine_name'=>trim($this->request->data['Cuisine']['cuisine_name']),
                            'NOT' => array('Cuisine.id' => $this->request->data['Cuisine']['id'])
                          )));
            if(!empty($Cuisine)) {
                $this->Session->setFlash('<p>'.__('Impossible d’ajouter votre spécialité culinaire', true).'</p>', 'default',
                                                  array('class' => 'alert alert-danger'));
            } else {
                $this->Cuisine->save($this->request->data,null,null);
                $this->Session->setFlash('<p>'.__('Votre spécialité culinaire a été enregistrée', true).'</p>', 'default',
                                                  array('class' => 'alert alert-success'));
                $this->redirect(array('controller' => 'Cuisines','action' => 'index'));
            }
        }
        $getStateData        = $this->Cuisine->findById($id);
        $this->request->data = $getStateData;
    }
}