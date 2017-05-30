<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    function statusIsFeatured() {
        var isFeatured = $("#StoreIsFeatured").val();
        if (isFeatured == "0") {
            $("#start").hide();
            $("#end").hide();
        } else {
            $("#start").show();
            $("#end").show();
        }
    }
    $(function () {

        $("document").ready(function () {
            statusIsFeatured();
            $("#StoreIsFeatured").change(function(){
                statusIsFeatured()
            });
        });

        $("#startdatepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $("#enddatepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });

</script>
<script type="text/javascript" src="<?php echo $siteUrl.'/assets/js/bootstrap-datetimepicker.min.js';?>"></script>
<link href="<?php echo $siteUrl.'/assets/css/bootstrap-datetimepicker.min.css';?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&v=3&sensor=false&amp;libraries=places,geometry"></script>
<div class="contain">
    <div class="contain">
        <h3 class="page-title">Editer Restaurant</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?php echo $siteUrl.'/admin/stores/index';?>">Gestion Restaurant</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Editer Restaurant</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i> Editer Restaurant
                        </div>
                    </div>

                    <div class="portlet-body form">
                        <div  class="sitePaddinner">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#contact" data-toggle="tab">Contact info</a></li>
                                <li><a href="#shop" data-toggle="tab">Restaurant info</a></li>
                                <!--<li><a href="#delivery" data-toggle="tab" onclick="return showMap();">Info livraison</a></li>-->
                                <li><a href="#order" data-toggle="tab">Info commande</a></li>
                                <li><a href="#commission" data-toggle="tab">Commission</a></li>
                                <li><a href="#invoice" data-toggle="tab">Période de facturation</a></li>
                                <li><a href="#MetaTags" data-toggle="tab">Meta Tags</a></li>
                                <li><a href="#isFeatured" data-toggle="tab">Is Featured ?</a></li>
                            </ul> 
                        </div>

						<?php
						echo $this->Form->create('Store',array('class'=>'form-horizontal',
																'type' => 'file')); ?>
                        <div class="tab-content"> 
                            <div class="tab-pane fade active in" id="contact">
                                <div class="row contain">
                                    <div class="col-md-offset-3 col-md-6 col-lg-4"> 
                                        <label id="contactError" class="error"></label>
                                    </div>
                                </div>

                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Prénom contact <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('contact_name',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">N° téléphone <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('contact_phone',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Email <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('contact_email',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div> <?php
										if($siteSetting['Sitesetting']['address_mode'] == 'Google') { ?>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Adresse <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('address',
															array('class' => 'form-control',
																	'label' => false,
																	'onfocus' =>'initialize(this.id)')); ?>
                                        </div>
                                    </div> <?php
										} else { ?>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Street <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('street_address',
																		array('class' => 'form-control',
																			  'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">State <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('store_state',
																array('type'=>'select',
																		'class'=>'form-control',
																		'options'=> array($states),
																		'onchange' => 'citiesList();',
																		'empty' => __('Select State'),
																		'label'=> false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">City <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('store_city',
																array('type'=>'select',
																		'class'=>'form-control',
																		'options'=> array($cities),
																		'onchange' => 'locationList();',
																		'empty' => 'Select City',
																		'label'=> false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Location <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('store_zip',
																array('type'=>'select',
																		'class'=>'form-control',
																		'options'=> array($locations),
																		'empty' => 'Select Location',
																		'label'=> false)); ?>
                                        </div>
                                    </div> <?php
										} ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="shop">
                                <div class="row contain">
                                    <div class="col-md-offset-3 col-md-6 col-lg-4"> 
                                        <label id="shopError" class="error"></label>
                                    </div>
                                </div>

                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nom du restaurant<span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('store_name',
										                            array('class' => 'form-control',
										                            	'value' => htmlspecialchars_decode($this->request->data['Store']['store_name']),
										                                  'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Téléphone restaurant<span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('store_phone',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Restaurant Logo</label> 
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('store_logo',
										                            array('type'  => 'file',
										                            	   'div'  => false,
										                                  'label' => false));
										        echo $this->Form->input('org_logo',
							                            array('class' => 'form-control',
							                            	  'type' => 'hidden',
							                                  'label' => false,
							                                  'value' => $this->request->data['Store']['store_logo'])); ?>

                                            <img class="img-responsive img_fields" src="<?php echo $siteUrl. '/storelogos/'.$this->request->data['Store']['store_logo']; ?>" alt="">

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Restaurant Banner</label> 
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('store_banner',
										                            array('type'  => 'file',
										                            	   'div'  => false,
										                                  'label' => false));
										        echo $this->Form->input('org_ban',
							                            array('class' => 'form-control',
							                            	  'type' => 'hidden',
							                                  'label' => false,
							                                  'value' => $this->request->data['Store']['store_banner'])); ?>
                                                                                            <?php
                                                                                            $storeBanner = $this->request->data['Store']['store_banner'];
                                                                                            $storeBanner1 = explode('.', $storeBanner);
                                                                                            if(!empty($storeBanner)){
                                                                                            if($storeBanner1[1] == "mp4"){
                                                                                                ?>
                                            <video width="320" height="240" controls>
                                                <source src="<?php echo $siteUrl. '/storebanner/original/'.$this->request->data['Store']['store_banner']; ?>" type="video/mp4">
                                            </video> 
                                                                                                    <?php
                                                                                            }else{
                                                                                            ?>
                                            <img class="img-responsive img_fields" src="<?php echo $siteUrl. '/storebanner/original/'.$this->request->data['Store']['store_banner']; ?>" alt="">
                                                                                            <?php }} ?>    
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Restaurant Banner Link</span></label>
                                        <div class="col-md-6 col-lg-4">
                                             <?php
                                                echo $this->Form->input('banner_image_link',
                                                            array('class' => 'form-control',
                                                                  'label' => false
                                                                )); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Horaires Restaurant</label> 
                                        <div class="col-md-6 col-lg-3 text-center"> 
                                            Premier service
                                        </div>
                                        <div class="col-md-3 text-center"> 
                                            Deuxième service
                                        </div>
                                    </div>
										<?= $this->Form->input('StoreTiming.id',
												array('type' => 'hidden',
														'label' => false));
										?>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Lundi</label> 
                                        <div class="col-md-6 col-lg-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="monday_first" class="slotTime"></div>
                                            </div> 
                                            <div class="timeappend">
                                                <span class="slider-time" id="monday_first_from">10:00 AM</span> - <span class="slider-time2" id="monday_first_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.monday_firstopen_time',
														array('type' => 'hidden',
																'id' => 'monday_first_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.monday_firstclose_time',
														array('type' => 'hidden',
																'id' => 'monday_first_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-md-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="monday_second" class="slotTime"></div>
                                            </div> 
                                            <div class="timeappend">
                                                <span class="slider-time" id="monday_second_from">10:00 AM</span> - <span class="slider-time2" id="monday_second_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.monday_secondopen_time',
														array('type' => 'hidden',
																'id' => 'monday_second_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.monday_secondclose_time',
														array('type' => 'hidden',
																'id' => 'monday_second_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-sm-2 margin-l-15 checkbox checkbox-inline">
											    <?php
												echo $this->Form->input('Fermeture',
														array('class'=>'',
																'hiddenField'=>false,
																'div' => false,
																'type' => 'checkbox',
																'id' => 'monday_status',
																'onchange' => 'closemask()',
																'name' => 'data[StoreTiming][monday_status]',
																'checked' => ($this->request->data['StoreTiming']['monday_status'] == 'Close')
																		? true : false,
																'value'=> 'Close')); ?>
                                        </div>				
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Mardi</label> 
                                        <div class="col-md-6 col-lg-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="tuesday_first" class="slotTime"></div>
                                            </div>  
                                            <div class="timeappend">
                                                <span class="slider-time" id="tuesday_first_from">10:00 AM</span> - <span class="slider-time2" id="tuesday_first_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.tuesday_firstopen_time',
														array('type' => 'hidden',
																'id' => 'tuesday_first_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.tuesday_firstclose_time',
														array('type' => 'hidden',
																'id' => 'tuesday_first_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-md-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="tuesday_second" class="slotTime"></div>
                                            </div> 
                                            <div class="timeappend">
                                                <span class="slider-time" id="tuesday_second_from">10:00 AM</span> - <span class="slider-time2" id="tuesday_second_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.tuesday_secondopen_time',
														array('type' => 'hidden',
																'id' => 'tuesday_second_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.tuesday_secondclose_time',
														array('type' => 'hidden',
																'id' => 'tuesday_second_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-sm-2 margin-l-15 checkbox checkbox-inline">
											    <?php
												echo $this->Form->input('Fermeture',
														array('class'=>'' ,
																'hiddenField'=>false,
																'div' => false,
																'type' => 'checkbox',
																'id' => 'tuesday_status',
																'onchange' => 'closemask()',
																'name' => 'data[StoreTiming][tuesday_status]',
																'checked' => ($this->request->data['StoreTiming']['tuesday_status'] == 'Close')
																		? true : false,
																'value'=> 'Close')); ?>
                                        </div>					
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Mercredi</label> 
                                        <div class="col-md-6 col-lg-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="wednesday_first" class="slotTime"></div>
                                            </div>   
                                            <div class="timeappend">
                                                <span class="slider-time" id="wednesday_first_from">10:00 AM</span> - <span class="slider-time2" id="wednesday_first_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.wednesday_firstopen_time',
														array('type' => 'hidden',
																'id' => 'wednesday_first_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.wednesday_firstclose_time',
														array('type' => 'hidden',
																'id' => 'wednesday_first_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-md-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="wednesday_second" class="slotTime"></div>
                                            </div> 
                                            <div class="timeappend">
                                                <span class="slider-time" id="wednesday_second_from">10:00 AM</span> - <span class="slider-time2" id="wednesday_second_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.wednesday_secondopen_time',
														array('type' => 'hidden',
																'id' => 'wednesday_second_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.wednesday_secondclose_time',
														array('type' => 'hidden',
																'id' => 'wednesday_second_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-sm-2 margin-l-15 checkbox checkbox-inline">
											    <?php
												echo $this->Form->input('Fermeture',
														array('class'=>'' ,
																'hiddenField'=>false,
																'div' => false,
																'type' => 'checkbox',
																'id' => 'wednesday_status',
																'onchange' => 'closemask()',
																'name' => 'data[StoreTiming][wednesday_status]',
																'checked' => ($this->request->data['StoreTiming']['wednesday_status'] == 'Close')
																		? true : false,
																'value'=> 'Close')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Jeudi</label> 
                                        <div class="col-md-6 col-lg-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="thursday_first" class="slotTime"></div>
                                            </div> 
                                            <div class="timeappend">
                                                <span class="slider-time" id="thursday_first_from">10:00 AM</span> - <span class="slider-time2" id="thursday_first_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.thursday_firstopen_time',
														array('type' => 'hidden',
																'id' => 'thursday_first_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.thursday_firstclose_time',
														array('type' => 'hidden',
																'id' => 'thursday_first_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-md-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="thursday_second" class="slotTime"></div>
                                            </div> 
                                            <div class="timeappend">
                                                <span class="slider-time" id="thursday_second_from">10:00 AM</span> - <span class="slider-time2" id="thursday_second_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.thursday_secondopen_time',
														array('type' => 'hidden',
																'id' => 'thursday_second_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.thursday_secondclose_time',
														array('type' => 'hidden',
																'id' => 'thursday_second_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-sm-2 margin-l-15 checkbox checkbox-inline">
											    <?php
												echo $this->Form->input('Fermeture',
														array('class'=>'' ,
																'hiddenField'=>false,
																'div' => false,
																'type' => 'checkbox',
																'id' => 'thursday_status',
																'onchange' => 'closemask()',
																'name' => 'data[StoreTiming][thursday_status]',
																'checked' => ($this->request->data['StoreTiming']['thursday_status'] == 'Close')
																		? true : false,
																'value'=> 'Close')); ?>
                                        </div>				
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Vendredi</label> 
                                        <div class="col-md-6 col-lg-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="friday_first" class="slotTime"></div>
                                            </div>    
                                            <div class="timeappend">
                                                <span class="slider-time" id="friday_first_from">10:00 AM</span> - <span class="slider-time2" id="friday_first_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.friday_firstopen_time',
														array('type' => 'hidden',
																'id' => 'friday_first_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.friday_firstclose_time',
														array('type' => 'hidden',
																'id' => 'friday_first_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-md-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="friday_second" class="slotTime"></div>
                                            </div>    
                                            <div class="timeappend">
                                                <span class="slider-time" id="friday_second_from">10:00 AM</span> - <span class="slider-time2" id="friday_second_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.friday_secondopen_time',
														array('type' => 'hidden',
																'id' => 'friday_second_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.friday_secondclose_time',
														array('type' => 'hidden',
																'id' => 'friday_second_closetime',
																'label' => false));
												?>
                                        </div> 
                                        <div class="col-sm-2 margin-l-15 checkbox checkbox-inline">
											    <?php
												echo $this->Form->input('Fermeture',
														array('class'=>'' ,
																'hiddenField'=>false,
																'div' => false,
																'type' => 'checkbox',
																'id' => 'friday_status',
																'onchange' => 'closemask()',
																'name' => 'data[StoreTiming][friday_status]',
																'checked' => ($this->request->data['StoreTiming']['friday_status'] == 'Close')
																		? true : false,
																'value'=> 'Close')); ?>
                                        </div>						
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Samedi</label> 
                                        <div class="col-md-6 col-lg-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="saturday_first" class="slotTime"></div>
                                            </div>   
                                            <div class="timeappend">
                                                <span class="slider-time" id="saturday_first_from">10:00 AM</span> - <span class="slider-time2" id="saturday_first_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.saturday_firstopen_time',
														array('type' => 'hidden',
																'id' => 'saturday_first_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.saturday_firstclose_time',
														array('type' => 'hidden',
																'id' => 'saturday_first_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-md-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="saturday_second" class="slotTime"></div>
                                            </div>    
                                            <div class="timeappend">
                                                <span class="slider-time" id="saturday_second_from">10:00 AM</span> - <span class="slider-time2" id="saturday_second_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.saturday_secondopen_time',
														array('type' => 'hidden',
																'id' => 'saturday_second_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.saturday_secondclose_time',
														array('type' => 'hidden',
																'id' => 'saturday_second_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-sm-2 margin-l-15 checkbox checkbox-inline">
											    <?php
												echo $this->Form->input('Fermeture',
														array('class'=>'' ,
																'hiddenField'=>false,
																'div' => false,
																'type' => 'checkbox',
																'onchange' => 'closemask()',
																'id' => 'saturday_status',
																'name' => 'data[StoreTiming][saturday_status]',
																'checked' => ($this->request->data['StoreTiming']['saturday_status'] == 'Close')
																		? true : false,
																'value'=> 'Close')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Dimanche</label> 
                                        <div class="col-md-6 col-lg-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="sunday_first" class="slotTime"></div>
                                            </div>  
                                            <div class="timeappend">
                                                <span class="slider-time" id="sunday_first_from">10:00 AM</span> - <span class="slider-time2" id="sunday_first_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.sunday_firstopen_time',
														array('type' => 'hidden',
																'id' => 'sunday_first_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.sunday_firstclose_time',
														array('type' => 'hidden',
																'id' => 'sunday_first_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-md-3 closed_mask"> 
                                            <div class="sliders_step1">
                                                <div id="sunday_second" class="slotTime"></div>
                                            </div>  
                                            <div class="timeappend">
                                                <span class="slider-time" id="sunday_second_from">10:00 AM</span> - <span class="slider-time2" id="sunday_second_to">12:00 PM</span>
                                            </div>
												<?= $this->Form->input('StoreTiming.sunday_secondopen_time',
														array('type' => 'hidden',
																'id' => 'sunday_second_opentime',
																'label' => false));
												?>
												<?= $this->Form->input('StoreTiming.sunday_secondclose_time',
														array('type' => 'hidden',
																'id' => 'sunday_second_closetime',
																'label' => false));
												?>
                                        </div>
                                        <div class="col-sm-2 margin-l-15 checkbox checkbox-inline">
											    <?php
												echo $this->Form->input('Fermeture',
														array('class'=>'' ,
																'hiddenField'=>false,
																'div' => false,
																'type' => 'checkbox',
																'id' => 'sunday_status',
																'onchange' => 'closemask()',
																'name' => 'data[StoreTiming][sunday_status]',
																'checked' => ($this->request->data['StoreTiming']['sunday_status'] == 'Close')
																		? true : false,
																'value'=> 'Close')); ?>
                                        </div>
                                    </div>

                                    <div class="form-group" style="display:none;">
                                        <label class="col-md-3 control-label">T.V.A <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="input-group"> <?php
													echo $this->Form->input('tax',
															array('class' => 'form-control',
																	'type'  => 'text',
                                                                                                                                        'value'=> '0.00',
																	'label' => false)); ?>
                                                <div class="input-group-addon">%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Cuisine <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('Cuisine.cuisine_id', array(
														'multiple' => 'multiple',
														'type' => 'select',
														'class' => 'form-control',
														'selected' => $getStoreCuisine
												)); ?>
                                        </div>
                                    </div>

                                    <div class="form-group profile-box clearfix">
                                        <label class="control-label col-lg-3 col-md-4 col-sm-5">Do You Wanna dispatch</label>
                                        <div class="col-lg-5 col-md-6 col-sm-6">
                                            <div class="radio-list">
                                                <label class="radio-inline"> <?php 
					                                    $option1 = array('Yes'  => 'Oui');
					                                    $option2 = array('No'   => 'Non');
					                                   	echo $this->Form->radio('dispatch',$option1,
					                           							array('checked'=>$option1,
					                           								'label'=>false,
					                           								'legend'=>false,    
					                           								'checked' =>true,
					                           								'hiddenField'=>false)); ?>
                                                </label>
                                                <label class="radio-inline"><?php
					                                	echo $this->Form->radio('dispatch',$option2,
					                           							array('checked'=>$option2,
					                           								'label'=>false,
					                           								'legend'=>false,
					                           								'hiddenField'=>false)); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group profile-box clearfix">
                                        <label class="control-label col-lg-3 col-md-4 col-sm-5">A emporter</label>
                                        <div class="col-lg-5 col-md-6 col-sm-6">
                                            <div class="radio-list">
                                                <label class="radio-inline"> <?php
					                                   	echo $this->Form->radio('collection',$option1,
					                           							array('checked'=>$option1,
					                           								'label'=>false,
					                           								'legend'=>false,    
					                           								'checked' =>true,
					                           								'hiddenField'=>false)); ?>
                                                </label>
                                                <label class="radio-inline"><?php
					                                	echo $this->Form->radio('collection',$option2,
					                           							array('checked'=>$option2,
					                           								'label'=>false,
					                           								'legend'=>false,
					                           								'hiddenField'=>false)); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group profile-box clearfix">
                                        <label class="control-label col-lg-3 col-md-4 col-sm-5">Livraison</label>
                                        <div class="col-lg-5 col-md-6 col-sm-6">
                                            <div class="radio-list">
                                                <label class="radio-inline"> <?php
					                                   	echo $this->Form->radio('delivery',$option1,
					                           							array('checked'=>$option1,
					                           								'label'=>false,
					                           								'legend'=>false,    
					                           								'checked' =>true,
					                           								'hiddenField'=>false)); ?>
                                                </label>
                                                <label class="radio-inline"><?php
					                                	echo $this->Form->radio('delivery',$option2,
					                           							array('checked'=>$option2,
					                           								'label'=>false,
					                           								'legend'=>false,
					                           								'hiddenField'=>false)); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group profile-box clearfix">
                                        <label class="control-label col-lg-3 col-md-4 col-sm-5">réservation sur place</label>
                                        <div class="col-lg-5 col-md-6 col-sm-6">
                                            <div class="radio-list">
                                                <label class="radio-inline"> <?php
					                                   	echo $this->Form->radio('bookatable',$option1,
					                           							array('checked'=>$option1,
					                           								'label'=>false,
					                           								'legend'=>false,    
					                           								'checked' =>true,
					                           								'hiddenField'=>false)); ?>
                                                </label>
                                                <label class="radio-inline"><?php
					                                	echo $this->Form->radio('bookatable',$option2,
					                           							array('checked'=>$option2,
					                           								'label'=>false,
					                           								'legend'=>false,
					                           								'hiddenField'=>false)); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group profile-box clearfix">
                                        <label class="control-label col-lg-3 col-md-4 col-sm-5">Type de restaurants </label>
                                        <div class="col-lg-5 col-md-6 col-sm-6">
                                            <div class="checkbox-list">
                                                <label class="checkbox-inline"> 
														<?php
					                                   	echo $this->Form->checkbox('family',
					                           							array('type' => 'checkbox',
					                           								'label'=>false,
					                           								'legend'=>false, 	
					                           								'hiddenField'=>false)); ?><?php echo __('Family');?>
                                                </label>
                                                <label class="checkbox-inline"> 
						                            	<?php   	
					                                   	echo $this->Form->checkbox('friends',
					                           							array('type' => 'checkbox',
					                           								'label'=>false,
					                           								'legend'=>false, 
					                           								'hiddenField'=>false)); ?><?php echo __('Friends');?>
                                                </label>
                                                <label class="checkbox-inline"> 
						                            	<?php
					                                   	echo $this->Form->checkbox('couple',
					                           							array('type' => 'checkbox',
					                           								'label'=>false,
					                           								'legend'=>false,
					                           								'hiddenField'=>false)); ?><?php echo __('Couple');?>
                                                </label>
                                                <label class="checkbox-inline"> 
						                            	<?php
					                                   	echo $this->Form->checkbox('business',
					                           							array('type' => 'checkbox',
					                           								'label'=>false,
					                           								'legend'=>false,
					                           								'hiddenField'=>false)); ?><?php echo __('Business');?> 
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">A propos du Restaurant </label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('store_description',
										                            array('class' => 'form-control',
										                            	  'type'  => 'textarea',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>	
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Email <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('User.username',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>						
                                </div>
                            </div>
                            <div class="tab-pane fade" id="order">
                                <div class="row contain">
                                    <div class="col-md-offset-3 col-md-6 col-lg-4"> 
                                        <label id="orderError" class="error"></label>
                                    </div>
                                </div>

                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Email pour commande <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="radio-list">
                                                <label class="radio-inline"> <?php 
			                                          echo $this->Form->radio('email_order',$option1,
			                                          							array('checked'=>$option1,
			                                          								'label'=>false,
			                                          								'legend'=>false,
			                                          								'checked' => 'checked',
			                                          								'hiddenField'=>false)); ?> 
                                                </label>
                                                <label class="radio-inline">  <?php 
			                                           echo $this->Form->radio('email_order',$option2,
			                                           							array('checked'=>$option2,
			                                           								'label'=>false,
			                                           								'legend'=>false,
			                                           								'hiddenField'=>false)); ?>  
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="emailOption" class="form-group">
                                        <label class="col-md-3 control-label">Commande Email <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('order_email',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">SMS Option<span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="radio-list">
                                                <label class="radio-inline"> <?php                    
			                                          echo $this->Form->radio('sms_option',$option1,
			                                          							array('checked'=>$option1,
			                                          								'label'=>false,
			                                          								'legend'=>false,
			                                          								'checked' => 'checked',
			                                          								'hiddenField'=>false)); ?> 
                                                </label>
                                                <label class="radio-inline">  <?php 
			                                           echo $this->Form->radio('sms_option',$option2,
			                                           							array('checked'=>$option2,
			                                           								'label'=>false,
			                                           								'legend'=>false,
			                                           								'hiddenField'=>false)); ?>  
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="smsOption" class="form-group">
                                        <label class="col-md-3 control-label"> Numéro de téléphone <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('sms_phone',
										                            array('class' => 'form-control',
										                                  'label' => false)); ?>
                                        </div>
                                    </div>							
                                </div>
                            </div>
                            <div class="tab-pane fade" id="delivery">
                                <div class="row contain">
                                    <div class="col-md-offset-3 col-md-6 col-lg-4"> 
                                        <label id="deliveryError" class="error"></label>
                                    </div>
                                </div>

                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Estimation temps de livraison <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">  <?php
												echo $this->Form->input('estimate_time',
														array('class' => 'form-control',
																'type'  => 'text',
																'label' => false)); ?>
                                        </div>
                                    </div> <?php
										if($siteSetting['Sitesetting']['address_mode'] == 'Google') { ?>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Commande Minimum <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">  <?php
													echo $this->Form->input('minimum_order',
																		array('class' => 'form-control',
																			  'type'  => 'text',
																			  'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Frais de livraison <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
													echo $this->Form->input('delivery_charge',
																		array('class' => 'form-control',
																			  'type'  => 'text',
																			  'label' => false)); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Distance livraison <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">
													 <?php
														echo $this->Form->input('delivery_distance',
																		array('class' => 'form-control',
																			  'type'  => 'text',
																			  'label' => false)); ?>

                                        </div>
                                        <a onclick="return showMap();" class="btn btn-success">Voir Carte</a>
                                    </div>
                                    <div id="googleMapShow" class="margin-b-15"></div><?php
										} else { ?>

                                    <div class="form-group">
                                        <div class="col-sm-9 col-sm-offset-3">
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <div class="labelname">City</div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="labelname"> <?php
																$searchBy = ($siteSetting['Sitesetting']['search_by'] == 'zip') ? 'Postcode' : 'Areaname';
																echo $searchBy; ?> 
                                                    </div>
                                                    <input type="hidden" id="searchBy" value="<?php echo $searchBy;?>">
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="labelname">Minimum Order</div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="labelname">Delivery Order</div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a onclick="appendDeliveryLocation();" class="btn btn-success">Add</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <?php

                                            if (!empty($deliveryLocation)) {
                                                foreach ($deliveryLocation as $delKey => $delVal) { ?>
                                    <div class="form-group" id="removeLocation_<?php echo $delKey; ?>">
                                        <div class="col-sm-9 col-sm-offset-3">
                                            <div class="row">
                                                <div class="col-sm-2"> <?php
                                                                    echo $this->Form->input('DeliveryLocation.city_name', [
                                                                        'class' => 'form-control',
                                                                        'type' => 'text',
                                                                        'id' => 'deliveryCity_' . $delKey,
                                                                        'name' => 'data[deliveryLocation][' . $delKey . '][city_name]',
                                                                        'onkeyup' => 'getCityName(this.id);',
                                                                        'placeholder' => 'City',
                                                                        'value' => $delVal['City']['city_name'],
                                                                        'label' => false
                                                                    ]); ?>
                                                </div>
                                                <div class="col-sm-2"><?php
                                                                    echo $this->Form->input('DeliveryLocation.location_name', [
                                                                        'class' => 'form-control deliveryLocationName',
                                                                        'type' => 'text',
                                                                        'id' => 'deliveryLocation_' . $delKey,
                                                                        'name' => 'data[deliveryLocation][' . $delKey . '][location_name]',
                                                                        'onkeyup' => 'getLocationName(this.id, ' . $delKey . ');',
                                                                        'placeholder' => $searchBy,
                                                                        'value' => ($siteSetting['Sitesetting']['search_by'] == 'zip') ? $delVal['Location']['zip_code'] : $delVal['Location']['area_name'],
                                                                        'label' => false
                                                                    ]); ?>
                                                </div>
                                                <div class="col-sm-2"><?php
                                                                    echo $this->Form->input('DeliveryLocation.minimum_order', [
                                                                        'class' => 'form-control',
                                                                        'type' => 'text',
                                                                        'id' => 'minimumOrder_' . $delKey,
                                                                        'name' => 'data[deliveryLocation][' . $delKey . '][minimum_order]',
                                                                        'placeholder' => 'Min Order',
                                                                        'value' => $delVal['DeliveryLocation']['minimum_order'],
                                                                        'label' => false
                                                                    ]); ?>
                                                </div>
                                                <div class="col-sm-2"><?php
                                                                    echo $this->Form->input('DeliveryLocation.delivery_charge', [
                                                                        'class' => 'form-control',
                                                                        'type' => 'text',
                                                                        'id' => 'deliveryCharge_' . $delKey,
                                                                        'name' => 'data[deliveryLocation][' . $delKey . '][delivery_charge]',
                                                                        'placeholder' => 'Del Charge',
                                                                        'value' => $delVal['DeliveryLocation']['delivery_charge'],
                                                                        'label' => false
                                                                    ]); ?>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a onclick="removeLocation(<?php echo $delKey; ?>);"
                                                       class="btn btn-danger">X</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <?php
                                                } ?>
                                    <script>var j = '<?php echo $delKey+1; ?>';</script> <?php
                                            } else { ?>
                                    <div class="form-group" id="removeLocation_0">
                                        <div class="col-sm-9 col-sm-offset-3">
                                            <div class="row">
                                                <div class="col-sm-2"> <?php
                                                                echo $this->Form->input('DeliveryLocation.city_name', [
                                                                    'class' => 'form-control',
                                                                    'type' => 'text',
                                                                    'id' => 'deliveryCity_0',
                                                                    'name' => 'data[deliveryLocation][0][city_name]',
                                                                    'onkeyup' => 'getCityName(this.id);',
                                                                    'placeholder' => 'City',
                                                                    'label' => false
                                                                ]); ?>
                                                </div>
                                                <div class="col-sm-2"><?php
                                                                echo $this->Form->input('DeliveryLocation.location_name', [
                                                                    'class' => 'form-control deliveryLocationName',
                                                                    'type' => 'text',
                                                                    'id' => 'deliveryLocation_0',
                                                                    'name' => 'data[deliveryLocation][0][location_name]',
                                                                    'onkeyup' => 'getLocationName(this.id, 0);',
                                                                    'placeholder' => 'Postcode',
                                                                    'label' => false
                                                                ]); ?>
                                                </div>
                                                <div class="col-sm-2"><?php
                                                                echo $this->Form->input('DeliveryLocation.minimum_order', [
                                                                    'class' => 'form-control',
                                                                    'type' => 'text',
                                                                    'id' => 'minimumOrder_0',
                                                                    'name' => 'data[deliveryLocation][0][minimum_order]',
                                                                    'placeholder' => 'Min Order',
                                                                    'label' => false
                                                                ]); ?>
                                                </div>
                                                <div class="col-sm-2"><?php
                                                                echo $this->Form->input('DeliveryLocation.delivery_charge', [
                                                                    'class' => 'form-control',
                                                                    'type' => 'text',
                                                                    'id' => 'deliveryCharge_0',
                                                                    'name' => 'data[deliveryLocation][0][delivery_charge]',
                                                                    'placeholder' => 'Del Charge',
                                                                    'label' => false
                                                                ]); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <?php
                                            } ?>
                                    <div class="appendDeliveryLocation"></div> <?php
										} ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="commission">
                                <div class="row contain">
                                    <div class="col-md-offset-3 col-md-6 col-lg-4"> 
                                        <label id="commissionError" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Restaurant Commission<span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4"> <?php
												echo $this->Form->input('commission',
										                            array('class' => 'form-control',
										                                  'label' => false,
										                                  'type' => 'text')); ?>
                                        </div>
                                    </div>	
                                </div>

                            </div>
                            <div class="tab-pane fade" id="invoice">
                                <div class="row contain">
                                    <div class="col-md-offset-3 col-md-6 col-lg-4"> 
                                        <label id="invoiceError" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">la période de facturation</label>
                                        <div class="col-md-6 col-lg-4"><?php

												$invoiceTimes = array('15day'  => '15jours',
																	  '30day' => '30jours');
												echo $this->Form->input('invoice_period',
															array('type'=>'select',
															 		'class'=>'form-control',
															 		'options'=> array($invoiceTimes),
															 		
															 		'label'=> false)); ?>
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
                            <div class="tab-pane fade" id="isFeatured">
                                 <div class="row contain">
                                    <div class="col-md-offset-3 col-md-6 col-lg-4"> 
                                        <label id="shopErrorx" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Statut vedette</label>
                                        <div class="col-md-6 col-lg-4">
                                            <?php
                                          echo   $this->Form->input('isFeatured', [
                                                    'type'  => 'select',
                                                    'class' => 'form-control',
                                                    'options' => array("0"=>"No", "1"=>"Yes"),
                                                    'label' => false,
                                                    'default'=>'3'
                                                    ]); ?>
                                        </div>
                                    </div>	
                                </div>
                                <div class="form-body" id="start">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Entrez la date de début</label>
                                        <div class="col-md-6 col-lg-4">
                                            <p><?php echo $this->Form->input('featured_start_time',array('id'=>'startdatepicker', 'type' => 'text','div' => false, 'label' => false, 'wrapInput' => false));?></p> 
                                        </div>
                                    </div>	
                                </div>
                                <div class="form-body" id="end">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Entrez la date de fin</label>
                                        <div class="col-md-6 col-lg-4">
                                            <p><?php echo $this->Form->input('featured_end_time',array('id'=>'enddatepicker', 'type' => 'text','div' => false, 'label' => false, 'wrapInput' => false));?></p> 
                                        </div>
                                    </div>	
                                </div>
                            </div>

                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-xs-5 col-xs-offset-3"> <?php
								  		echo $this->Form->hidden('id');
										echo $this->Form->hidden('User.id');
										echo $this->Form->hidden('Store.id');
										echo $this->Form->button('<i class="fa fa-check"></i> '.__('Submit'),
				                              			array('class'=>'btn purple',
				                              				'onclick' => 'return validateStoreAddEdit();')); ?>
                                </div>
                            </div>  
                        </div> <?php
						echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

