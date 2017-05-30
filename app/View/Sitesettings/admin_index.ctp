<div class="contain">
    <div class="contain">
        <h3 class="page-title">Réglages site</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="#">Réglages site</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i> Réglages site
                        </div>
                    </div>

                    <div class="portlet-body form">
                        <div class="sitePaddinner">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#site" data-toggle="tab">Site</a></li>
                                <li><a href="#contact" data-toggle="tab">Contact</a></li>
                                <li><a href="#location" data-toggle="tab">Lieu</a></li>
                                <li><a href="#analytics" data-toggle="tab">Code Analytics</a></li>
                                <li><a href="#mail" data-toggle="tab">Paramètre de messagerie</a></li>
                                <li><a href="#invoice" data-toggle="tab">Facture</a></li>
                                <li><a href="#offline" data-toggle="tab">Hors ligne</a></li>
                                <li><a href="#sms" data-toggle="tab">SMS</a></li>
                                <li><a href="#MetaTags" data-toggle="tab">Meta Tags</a></li>
                                <li><a href="#Language" data-toggle="tab">langage</a></li>
                                <li><a href="#mailchimp" data-toggle="tab">MailChimp</a></li>
                                <li><a href="#facebook" data-toggle="tab">Facebook</a></li>
                                <li><a href="#google" data-toggle="tab">Google+</a></li>
                                <li><a href="#pusher" data-toggle="tab">notification directe</a></li>
                            </ul>
                        </div>
						<?php
							echo $this->Form->create('Sitesetting',array('class' => 'form-horizontal',
																	 'type'  => 'file')); ?>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="site">
                                <div class="form-body">
                                    <div class="row contain">
                                        <div class="col-md-offset-3 col-md-6 col-lg-4"> 
                                            <label id="siteError" class="error"></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Site Nom <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('site_name',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Logo </label>
                                        <div class="col-md-6 col-lg-4"> <?php
                                				echo $this->Form->input('site_logo',
                                								array('label' => false,
                                									  'class' => 'required',
                                									  'type'  => 'file')); ?>
                                            <div class="col-sm-4 backlogocol"><img onerror="this.onerror=null;this.src='<?php echo $siteUrl."/img/no-imge.jpg"; ?>'" src="<?php echo $siteUrl?>/siteicons/logo.png" height="70" width="288"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Site Fav Icon </label>
                                        <div class="col-md-6 col-lg-4"> <?php
                                				echo $this->Form->input('site_fav',
                                								array('label' => false,
                                									  'class' => 'required',
                                									  'type'  => 'file')); ?>
                                            <div class="col-sm-4 backlogocol"><img onerror="this.onerror=null;this.src='<?php echo $siteUrl."/img/no-imge.jpg"; ?>'" src="<?php echo $siteUrl?>/siteicons/fav.ico" height="60" width="60"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Mode d'adresse <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="radio-list">
                                                <label class="radio-inline"> <?php
														$option1 = array('Google'  => 'Google');
														$option2 = array('Normal' => 'Normal');

														echo $this->Form->radio('address_mode',$option1,
																array('checked'=>$option1,
																		'label'=>false,
																		'legend'=>false,
																		'checked' => 'checked',
																		'hiddenField'=>false)); ?>
                                                </label>
                                                <label class="radio-inline">  <?php
														echo $this->Form->radio('address_mode',$option2,
																array('checked'=>$option2,
																		'label'=>false,
																		'legend'=>false,
																		'hiddenField'=>false)); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Rechercher par option <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="radio-list">
                                                <label class="radio-inline"> <?php
			                                          	$option1 = array('area' => 'Nom de la région');
														$option2 = array('zip'  => 'Code postal');
                    
			                                          	echo $this->Form->radio('search_by',$option1,
			                                          							array('checked'=>$option1,
			                                          								'label'=>false,
			                                          								'legend'=>false,
			                                          								'checked' => 'checked',
			                                          								'hiddenField'=>false)); ?> 
                                                </label>
                                                <label class="radio-inline">  <?php 
			                                           	echo $this->Form->radio('search_by',$option2,
			                                           							array('checked'=>$option2,
			                                           								'label'=>false,
			                                           								'legend'=>false,
			                                           								'hiddenField'=>false)); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>		
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Lien de la page d'accueil youtube <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('homepage_youtube_link',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contact">
                                <div class="form-body">
                                    <div class="row contain">
                                        <div class="col-md-offset-3 col-md-6 col-lg-4"> 
                                            <label id="contactError" class="error"></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Admin Nom <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('admin_name',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Admin Email <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('admin_email',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Contactez-nous Email <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('contact_us_email',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Facture Email <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('invoice_email',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>	
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Site Numéro du contact <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('contact_phone',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>	
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Commande Email <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('order_email',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>							
                                </div>

                            </div>
                            <div class="tab-pane fade" id="location">
                                <div class="form-body">
                                    <div class="row contain">
                                        <div class="col-md-offset-3 col-md-6 col-lg-4"> 
                                            <label id="locationError" class="error"></label>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Site Addresse <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('site_address',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>	
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Site Pays <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('site_country',
															array('type'=>'select',
															 		'class'=>'form-control',
															 		'options'=> array($countries),
															 		'onchange' => 'stateFillters();',
															 		'empty' => 'Sélectionnez le pays',
															 		'label'=> false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Site Departement <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('site_state',
															array('type'=>'select',
															 		'class'=>'form-control',
															 		'options'=> array($states),
															 		'onchange' => 'cityFillters();',
															 		'empty' => __('Select State'),
															 		'label'=> false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Site Ville <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('site_city',
															array('type'=>'select',
															 		'class'=>'form-control',
															 		'options'=> array($cities),
															 		'onchange' => 'locationLists();',
															 		'empty' => 'Select City',
															 		'label'=> false)); ?>
                                        </div>
                                    </div>	
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Site Lieu <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('site_zip',
															array('type'=>'select',
															 		'class'=>'form-control',
															 		'options'=> array($locations),
															 		'empty' => 'Select Location',
															 		'label'=> false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Site fuseau horaire <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('site_timezone',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>								
                                </div>
                            </div>
                            <div class="tab-pane fade" id="analytics">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Google Code Analytics </label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('google_analytics',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>	
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Zopim Chat Code </label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('woopra_analytics',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mail">
                                <div class="form-body">	
                                    <label id="mailError" class="error"></label>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Mail Option <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="radio-list">

                                                <label class="radio-inline smtp"> <?php  
			                                          $options1 = array('SMTP' 	 => 'SMTP');
			                                          $options2 = array('Normal' => 'Normal'); 
                    
			                                          echo $this->Form->radio('mail_option',$options1,
			                                          							array('checked'=>$options1,
			                                          								'label'=>false,
			                                          								'legend'=>false,
			                                          								'checked' => 'checked',
			                                          								'hiddenField'=>false)); ?> 
                                                </label>
                                                <label class="radio-inline smtp">  <?php 
			                                           echo $this->Form->radio('mail_option',$options2,
			                                           							array('checked'=>$options2,
			                                           								'label'=>false,
			                                           								'legend'=>false,
			                                           								'hiddenField'=>false)); ?>  
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="smtp">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">SMTP Host <span class="star">*</span></label>
                                            <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('smtp_host',
											                            array('class' => 'form-control',
											                                  'label' => false)); ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label">SMTP Port <span class="star">*</span></label>
                                            <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('smtp_port',
											                            array('class' => 'form-control',
											                                  'label' => false)); ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label">SMTP Username<span class="star">*</span></label>
                                            <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('smtp_username',
											                            array('class' => 'form-control',
											                                  'label' => false)); ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label">SMTP Password <span class="star">*</span></label>
                                            <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('smtp_password',
											                            array('class' => 'form-control',
											                                  'label' => false)); ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="tab-pane fade" id="invoice">
                                <div class="form-body">
                                    <label id="invoiceError" class="error"></label>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">VAT No</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('vat_no',
										                            array('class' => 'form-control',
										                            	  'type'  => 'text',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>				
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">VAT (%)</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('vat_percent',
										                            array('class' => 'form-control',
										                            	  'type'  => 'text',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>			
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Frais sur carte bancaire (%)</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('card_fee',
										                            array('class' => 'form-control',
										                            	  'type'  => 'text',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>	
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Période de facturation</label>
                                        <div class="col-md-6 col-lg-4"> <?php

												$invoiceTimes = array('7 days'  => '7jours',
																	  '15 days' => '15jours');

												echo $this->Form->input('invoice_duration',
															array('type'=>'select',
															 		'class'=>'form-control',
															 		'options'=> array($invoiceTimes),
															 		'empty' => 'Select Invoice Period Time',
															 		'label'=> false)); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="offline">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Hors ligne Statut <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="radio-list">

                                                <label class="radio-inline"> <?php  
			                                          $options3 = array('Yes' => 'Yes');
			                                          $options4 = array('No'  => 'No'); 
                    
			                                          echo $this->Form->radio('offline_status',$options3,
			                                          							array('checked'=>$options3,
			                                          								'label'=>false,
			                                          								'legend'=>false,
			                                          								'checked' => 'checked',
			                                          								'hiddenField'=>false)); ?> 
                                                </label>
                                                <label class="radio-inline">  <?php 
			                                           echo $this->Form->radio('offline_status',$options4,
			                                           							array('checked'=>$options4,
			                                           								'label'=>false,
			                                           								'legend'=>false,
			                                           								'hiddenField'=>false)); ?>  
                                                </label>

                                            </div>
                                        </div>
                                    </div>	
                                    <div class="form-group" id="offlineReason">
                                        <label class="col-md-3 control-label">Offline Notes</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('offline_notes',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>	
                                </div>											

                            </div>
                            <div class="tab-pane fade" id="sms">
                                <div class="form-body">
                                    <label id="smsError" class="error"></label>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">SMS Token id</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('sms_token',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>	
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Sms Auth id</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('sms_id',
										                            array('class' => 'form-control',
										                            	  'type'  => 'text',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>	
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Source Nombre</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('sms_source_number',
										                            array('class' => 'form-control',
										                                  'label' => false));
										        echo $this->Form->hidden('id'); ?>
                                        </div>
                                    </div>	
                                </div>
                            </div>
                            <div class="tab-pane fade" id="MetaTags">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Meta Titres</label>
                                        <div class="col-md-6 col-lg-4"><?php

												echo $this->Form->input('meta_title',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>	
                                </div>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Meta Mots clés</label>
                                        <div class="col-md-6 col-lg-4"><?php

												echo $this->Form->input('meta_keywords',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>	
                                </div>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Meta Descriptions</label>
                                        <div class="col-md-6 col-lg-4"><?php

												echo $this->Form->input('meta_description',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>	
                                </div>
                            </div>

                            <div class="tab-pane fade" id="Language">
                                <div class="form-body">
                                    <label id="languageError" class="error"></label>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Default langage <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="radio-list">

                                                <label class="radio-inline"> <?php

													$others = ($this->request->data['Sitesetting']['other_language']) ?
																$this->request->data['Sitesetting']['other_language'] :
																"Autres";


			                                          $optionss1 = array(1 => 'English');
			                                          $optionss2 = array(2  => $others); 
	                
			                                          echo $this->Form->radio('default_language',$optionss1,
			                                          							array('checked'=>$optionss1,
			                                          								'label'=>false,
			                                          								'legend'=>false,
			                                          								'checked' => 'checked',
			                                          								'hiddenField'=>false)); ?> 
                                                </label>
                                                <label class="radio-inline">  <?php 
			                                           echo $this->Form->radio('default_language',$optionss2,
			                                           							array('checked'=>$optionss2,
			                                           								'label'=>false,
			                                           								'legend'=>false,
			                                           								'hiddenField'=>false)); ?>  
                                                </label>
                                                <a class="otherLanguage">Editer</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="others" class="form-body" style="display:none">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"> Other langage<span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="radio-list"> <?php
													echo $this->Form->input('other_language',
											                            array('class' => 'form-control',
											                                  'label' => false)); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mailchimp">
                                <div class="form-body">
                                    <label id="mailchimpError" class="error"></label>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Key</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('mailchimp_key',
													array('class' => 'form-control',
														'type'  => 'text',
														'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">List id</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('mailchimp_list_id',
													array('class' => 'form-control',
														'type'  => 'text',
														'label' => false)); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="facebook">
                                <div class="form-body">
                                    <label id="facebookError" class="error"></label>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Api id</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('facebook_api_id',
													array('class' => 'form-control',
														'type'  => 'text',
														'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Secret key</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('facebook_secret_key',
													array('class' => 'form-control',
														'type'  => 'text',
														'label' => false)); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="google">
                                <div class="form-body">
                                    <label id="googleError" class="error"></label>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Api id</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('google_api_id',
													array('class' => 'form-control',
														'type'  => 'text',
														'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Secret key</label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('google_secret_key',
													array('class' => 'form-control',
														'type'  => 'text',
														'label' => false)); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pusher">
                                <div class="form-body">
                                    <label id="pusherError" class="error"></label>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Pusher Key</label>
                                        <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('pusher_key',
														array('class' => 'form-control',
															'type'  => 'text',
															'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Pusher Secret Key</label>
                                        <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('pusher_secret',
														array('class' => 'form-control',
															'type'  => 'text',
															'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">App id</label>
                                        <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('pusher_id',
														array('class' => 'form-control',
															'type'  => 'text',
															'label' => false)); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
							  			<?php
					                  		echo $this->Form->button('<i class="fa fa-check"></i>'.__('Submit'),
					                  								           array('class'=>'btn purple',	
					                  										'onclick' => 'return validate();')); 
						                ?>
                                </div>
                            </div>
                        </div>
						<?php echo $this->Form->end(); ?>
                    </div>
                </div>
                <!-- END PORTLET-->
            </div>
        </div>
    </div>
</div>