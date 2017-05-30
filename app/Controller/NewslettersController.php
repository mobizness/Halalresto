<?php

/* janakiraman */
App::uses('AppController','Controller');
App::uses('CakeEmail', 'Network/Email');

class NewslettersController extends AppController {
  public $helpers = array('Html','Form', 'Session', 'Javascript');
  public $uses    = array('Customer');
  public $components = array('Updown');
  /**
   * NewslettersController::admin_index()
   * Newsletter Management
   * @return void
   */
  public function admin_index() {
  	$customer_list = $this->Customer->find('all',array(
                                'conditions'=>array(
                                'Customer.news_letter_option'=>'Yes')));
    $this->set('customer_list',$customer_list);
  } 
  /**
   * NewslettersController::admin_sendmail()
   * Newsletter Maill Process
   * @return void
   */
  public function admin_sendMailAll() {
  	//echo "<pre>"; print_r($this->request->data);echo "</pre>";die();
  } 
  /**
   * NewslettersController::admin_sendMail()
   * Send A Newsletter To SelectedCustomer
   * @return void
   */
  public function admin_sendMail(){
    
  }


  public function admin_sendselectcustomer() {

    if (!empty($this->request->data['Newsletter']['email'])) {
        $emailList = implode($this->request->data['Newsletter']['email'], ',');
        $this->set(compact('emailList'));
    } else {

      if (!empty($this->request->data['Newsletter'])) {

        $subject  = $this->request->data['Newsletter']['subject'];
        $tomail   = $this->request->data['Newsletter']['to'];
        $tomails  = explode(',', $tomail);
        $content  = $this->request->data['Newsletter']['content'];
        $source   = $this->siteUrl.'/siteicons/logo.png';
        $siteName = $this->siteSetting['Sitesetting']['site_name'];

        $storeEmail = $this->siteSetting['Sitesetting']['admin_email'];

        foreach ($tomails as $key => $tomail) {

          $mailContent = $content;
          $email = new CakeEmail();
          $email->template('register');
          $email->emailFormat('html');
          $email->from($storeEmail);
          $email->to($tomail);
          $email->subject($subject);
          $email->viewVars(array('mailContent' => $mailContent,
                                  'source'     => $source,
                                  'storename'  => $siteName));
          $email->send();

        }

        $this ->Session->setFlash('<p>'.__('La Newsletter a été envoyée avec succès', true).'</p>', 'default', 
                                                      array('class' => 'alert alert-success'));
        return $this->redirect(array('controller'=>'newsletters', 'action' => 'index', 'admin' =>true));

      }
    }
  }

  public function admin_sendall() {

    if(isset($this->request->data['Newsletter'])) {

      $customerEmail = $this->Customer->find('list',array(
                          'conditions'=>array('Customer.news_letter_option'=>'Yes'),
                          'fields' => array('customer_email')));

      $source     = $this->siteUrl.'/siteicons/logo.png';
      $siteName   = $this->siteSetting['Sitesetting']['site_name'];
      $storeEmail = $this->siteSetting['Sitesetting']['admin_email'];

      $subject    = $this->request->data['Newsletter']['subject'];
      $content    = $this->request->data['Newsletter']['content'];

      foreach ($customerEmail as $key => $tomail) {

        $mailContent = $content;

        $email = new CakeEmail();
        $email->template('register');
        $email->emailFormat('html');
        $email->from($storeEmail);
        $email->to($tomail);
        $email->subject($subject);
        $email->viewVars(array('mailContent' => $mailContent,
                                'source'     => $source,
                                'storename'  => $siteName));        
        $email->send();
      }

      $this ->Session->setFlash('<p>'.__('La Newsletter a été envoyée avec succès', true).'</p>', 'default', 
                                                    array('class' => 'alert alert-success'));
      return $this->redirect(array('controller'=>'newsletters', 'action' => 'index'));
    }
  }

  public function uploadImage() {

      $imagesizedata = getimagesize($this->params['form']['file']['tmp_name']);
      if ($imagesizedata) {

        if (!empty($this->params['form']['file'])) {

            $imagePath = WWW_ROOT."images".DS."newsletter";
            $imagePathUrl = $this->siteUrl.'/'.'images'.'/'.'newsletter';
            $imageName = $this->Updown->uploadFile($this->params['form']['file'],$imagePath);
            echo $imagePathUrl.'/'.$imageName['refName'];
            exit();
        }
      }
      exit();
  }

}