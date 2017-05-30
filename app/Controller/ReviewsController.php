<?php
/* janakiraman */
App::uses('AppController','Controller');
class ReviewsController extends AppController {    
  public $helpers = array('Html','Form', 'Session', 'Javascript');  
  public $uses    = array('Review','Store');

  // Review Management
  public function admin_list($id = null) {
    $id = $this->params['pass'];
    if (!empty($id)){
      $Review_list = $this->Review->find('all',array(
                    'conditions'=>array(
                      'Review.store_id'=>$id[0],
                      'NOT'=>array('Review.status'=>3)),
                    'order'=>array('Review.id DESC'),
                    //'group'=>array('Review.store_id')
                    ));

    } else {
      $Review_list = $this->Review->find('all',array(
                    'conditions'=>array(
                      'NOT'=>array('Review.status'=>3)),
                    'order'=>array('Review.id DESC'),
                    //'group'=>array('Review.store_id')
                    )); 
    }  	
    $store_list  = $this->Store->find('list',array(
                              'conditions'=>array(
                                'NOT'=>array(
                                  'Store.status'=>3)),
                              'fields'=>array('Store.id','Store.store_name'))); 
    $this->set(compact('store_list','Review_list'));
    //$this->set('Review_list',$Review_list);
  }
  
}