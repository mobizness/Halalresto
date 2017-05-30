<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript">

		/*if (top !=self) {
		   top.location=self.location;
		}*/
		</script>

		<title> <?php 

			echo (!empty($metaTitle)) ? $metaTitle : $title_for_layout; ?> </title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="description" content="<?php echo $metaDescriptions; ?>" />
		<meta name="keywords" content="<?php echo $metakeywords; ?>" /> 
		<meta http-equiv="X-FRAME-OPTIONS" content="DENY"><?php
		echo $this->Html->meta('icon', $this->Html->url($siteUrl.'/siteicons/fav.ico')); ?>

		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/bootstrap.min.css" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/font-awesome.css" type="text/css" media="all">
		<!-- <link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/jquery.bootstrap-touchspin.css" type="text/css" media="all"> -->
		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/bootstrap-select.css" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/jquery.mCustomScrollbar.css" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/datepicker.css" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/common.css" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/jquery.dataTables.min.css" type="text/css" media="all">

		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/owl.carousel.css" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/owl.theme.css" type="text/css" media="all">

		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/products.css" type="text/css" media="all">
		
		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/common_new.css" type="text/css" media="all">

		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/mobile.css" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo $this->webroot; ?>frontend/css/mobile_1.css" type="text/css" media="all">		

		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,400italic" rel="stylesheet" type="text/css">
		<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>

	</head>
	<body onload="$('#thanksmsg').modal('show');"> <?php
	
	/*if($this->request->params['controller'] == "searches" &&
			$this->request->params['action'] == 'index') { ?>
		<div class="indexBnner" style="height: 673px;"> <?php
	} */?>
	<?php echo $this->element('frontend/topheader'); ?>
	<?php echo $this->Session->flash(); ?>

	<?php if ($this->request->params['action'] != 'storeitems') {
 		echo ' <div class="middle_height">';
 	} ?>
		<?php echo $this->fetch('content'); ?>
	<?php if ($this->request->params['action'] != 'storeitems') {
 		echo '</div>';
 	} ?>
		<footer id="footer" class="pluginFooter">
	       <div id="footer-copyright">
	          <div class="container">
	             <div class="row">
	                <div class="col-sm-3 hidden-xs clearfix text-left">
	                    <!-- <div class="flickr-widget widget">
	                        <p><a href="<?php echo $siteUrl.'/restaurant'; ?>">  <?php echo __('Restaurant Login', true); ?></a></p>
	                        <p><a href="<?php echo $siteUrl.'/restaurantSignup'; ?>">  <?php echo __('Restaurant Signup', true); ?></a></p>
	                        <p><a href="javascript:void(0);">  <?php echo __('FAQ', true); ?></a></p>
	                        <p><a href="javascript:void(0);">  <?php echo __('Feedback', true); ?></a></p>
	                        <p><a href="<?php echo $siteUrl.'/contactUs'; ?>">  <?php echo __('Contact Us', true); ?></a></p>
	                        <p><a href="javascript:void(0);">  <?php echo __('Review', true); ?></a></p>
	                        <p><a href="javascript:void(0);">  <?php echo __('Gift Voucher', true); ?></a></p>
	                        <p><a href="javascript:void(0);">  <?php echo __('Blog', true); ?></a></p>
	                        <p><a href="javascript:void(0);">  <?php echo __('Todays Deals', true); ?></a></p>
	                        <p><a href="javascript:void(0);">  <?php echo __('Terms & Conditions', true); ?></a></p>
	                        <p><a href="javascript:void(0);">  <?php echo __('Privacy', true); ?></a></p>

	                   </div> -->


	                   <div class="flickr-widget widget text-left">
	                   		<h5> <?php echo __('Useful links', true); ?></h5>
	                   		<p class="col-xs-12 no-margin-LR no-padding"><a href="<?php echo $siteUrl.'/faq'; ?>">  <?php echo __('FAQ', true); ?></a></p>
	                   		<p class="col-xs-12 no-margin-LR no-padding"><a href="<?php echo $siteUrl.'/contactUs'; ?>">  <?php echo __('Contact Us', true); ?></a></p>
	                   		<p class="col-xs-12 no-margin-LR no-padding"><a href="<?php echo $siteUrl.'/notice'; ?>">  <?php echo __('Mentions légales', true); ?></a></p>
	                   </div>
	                </div>
	                <div class="col-sm-3 hidden-xs clearfix text-left">
	               		<div class="flickr-widget widget">
	                		<h5>  <?php echo __('Who are we', true); ?></h5>
	                   		<p class="col-xs-12 no-margin-LR no-padding"><a href="<?php echo $siteUrl.'/aboutus'; ?>">  <?php echo __('About Us', true); ?></a></p>
	                   		<p class="col-xs-12 no-margin-LR no-padding"><a href="<?php echo $siteUrl.'/cgv'; ?>">  <?php echo __('CGV', true); ?></a></p>
	                   		<p class="col-xs-12 no-margin-LR no-padding"><a href="http://www.leguideduhalal.fr">  <?php echo __('Le Guide du halal', true); ?></a></p>
	                   	</div>
	                </div>
	                
	                <div class="col-sm-3 clearfix">
	                    
						<div class="text-left visamargin">
	                        <a class="btn btn-sm" target="_blank" href="https://www.facebook.com/HALAL-RESTO-174126233017569/"><i class="fa fa-facebook"></i></a>
	                        <a class="btn btn-sm" target="_blank" href="https://twitter.com/halal_resto"><i class="fa fa-twitter"></i></a>
	                        <a class="btn btn-sm" target="_blank" href="https://www.instagram.com/halal_resto/"><i class="fa fa-instagram"></i></a>
	                    </div>
						<div class="text-left visamargin margin-top-20">
							<a href="#" class="pull-left"><img src="<?php echo $this->webroot; ?>frontend/images/paypal.png" height="25"></a>
							<div class="clearfix"></div>
							<a href="#" class="pull-left margin-top-10"><img src="<?php echo $this->webroot; ?>frontend/images/merge.png"></a>
						</div>
	                </div>
					<!-- <div class="col-sm-3 clearfix">	                    
						<div class="text-left visamargin">
	                        <a class="linktonew" href="www.leguideduhalal.fr">legales Le Guide du Halal</a>
	                    </div>
	                </div> -->

	                <div class="col-xs-12 clearfix">
	                   <p class="copyright"> 2017 &copy;
	                      <a href="<?php echo $siteUrl; ?>">  <?php echo __($siteSetting['Sitesetting']['site_name'], true); ?></a> par <?php echo $siteSetting['Sitesetting']['site_name']; ?>
	                         <!-- <?php echo __('All Rights Reserved', true); ?>. -->
	                   </p>

	                </div>
	             </div>
	          </div>
	       </div>
	    </footer>
	    <div class="scrollTop">
	    	<img alt="scroll_up" title="scroll_up" src="<?php echo $siteUrl.'/frontend/images/scroll_up.png'; ?>">
	    </div>

	     <?php

	/*if($this->request->params['controller'] == "searches" &&
			$this->request->params['action'] == 'index') { ?>
		</div> <?php
	}*/

	if($this->request->params['action'] != 'stores') { ?>
		<!-- Page refresh loading image -->
	    <div class="ui-loader">
	        <div class="spinner">
	        	<div class="spinner-icon"></div>
	        </div>
	    </div> <?php
	} 


	$stripeController = array('checkouts', 'customers');

	if (in_array($this->request->params['controller'], $stripeController)) { ?>
		<script type="text/javascript" src="https://js.stripe.com/v2/"> </script> <?php

	} ?>
	<!--<script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/jquery-1.11.3.js"></script>-->
	<script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/bootstrap-select.js"></script>
	<script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/codex-fly.js"></script>
	<script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/jquery.bootstrap-touchspin.js"></script>
	<script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/common.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/customer.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/jquery.mCustomScrollbar.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/owl.carousel.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->webroot; ?>frontend/js/onlyaddress.js"></script>
	 <?php
		if (!empty($loggedUser)) {
    		echo $this->Html->script(array(
			        '//js.pusher.com/2.2/pusher.min.js',
			        'jquery.gritter.min.js',
			        'PusherNotifier.js'
			   	));
		}  ?>

	<script type="text/javascript">
		$(document).ready(function() {
		    var topPart = $('.header');
		    var pos = topPart.offset();
		    $(window).scroll(function() {
		        if ($(this).scrollTop() > pos.top + topPart.height()) {
		            $('.scrollTop').show();
		        } else if ($(this).scrollTop() <= pos.top) {
		            $('.scrollTop').hide();
		        }
		    });
		    $('.scrollTop').click(function() {
		        $("html, body").animate({scrollTop: 0});
		    });
		});
	</script>
    <script type="text/javascript">   
    	var loggedUser = "<?php echo (isset($loggedUser['role_id'])) ? $loggedUser['role_id'] : ''; ?>";    	
    	if (loggedUser == 4) {
    		sessionCheck();
    	} 	
		var rp = "<?php echo $siteUrl.'/'; ?>";
		var publishKey = "<?php echo $publishKey; ?>";
		var countryCode = "<?php echo $siteSetting['Country']['iso']; ?>";
		var addressMode = "<?php echo $siteSetting['Sitesetting']['address_mode']; ?>";
		var controller 	= "<?php echo $this->params['controller']; ?>";
		var action 		= "<?php echo $this->params['action']; ?>";
		function sessionCheck() {
			idleTimer = null;
			idleState = false;
			idleWait = 1800000;
			$('*').bind('click mousemove keydown scroll', function () {
				clearTimeout(idleTimer);
				if (idleState == true) { 
					//$("#logid").val("Not in Idle");						
				}
				idleState = false;
				idleTimer = setTimeout(function () { 
					window.location.href = rp+'customer/users/userLogout';
					idleState = true; }, idleWait);
			});
			$("body").trigger("mousemove");
		}
		$(window).load(function() {
			$('.ui-loader').hide();
		});
		$(document).ready(function(){
           	$(window).trigger('resize');
		   	doResize();
		   	$(window).on('resize', doResize);
		   	clearConsole();
        });
        
		function doResize(){
			var navbar_height = $(".navbar").height();				
			//middle minimum height
			var footer_height = $("footer").height();			
			var win_height = $(window).height();						
			var middle_height = win_height - ( navbar_height + footer_height ) + 10 ;/* -25 for footer paddings*/	
			if( $(window).width() > 767 ) {
                $(".middle_height").css({"min-height":middle_height});
                $(".loginBg").css({"min-height":middle_height});
            } else {
                $(".leftsideBar").css({ "padding-top": 0 });
                $(".middle_height").css({"min-height":win_height});
                $(".loginBg").css({"min-height":win_height});

                $(".leftsideBar h1").click(function() {
					$(this).next(".maincategory").slideToggle();
					$(this).toggleClass("active");				
				});
            }
		}

		function searchProducts() {
			$('.error').hide();
			$('.ui-loader').show();
			$('#messageError').hide();
			var searchKey = $('#searchKey').val();
			var searchKeyCount = $('#searchKey').val().length;
			var noresult = 0;
			//var searchProduct = getCookie("searchProduct");
			if (searchKey == '') {
				$(".searchMenuForm").after("<label class='error'> <?php echo __('Veuillez saisir le nom du plat'); ?> </label>");
				$('.ui-loader').hide();
			} else if (searchKeyCount < 3) {
				$(".searchMenuForm").after("<label class='error'> <?php echo __('Please enter minimum 3 letters of food name'); ?> </label>");
				$('.ui-loader').hide();
			} else {
				var countCategory = $('#countCategory').val();
				count = 0;
				$("#filtterByCategory").html('');
				for (var i = 0; i < countCategory; i++) {					
					var getvalue = $('#check'+i).val();
					var splits   = getvalue.split('_');
					var id		 = splits[0];
					var storeId  = splits[1];
					$.post(rp+'searches/filtterByCategory', {'id': id,'storeId':storeId, 'count':count, 'searchKey' : searchKey}, function (response) {
						if (response != '') {
							noresult = 1;
							$("#filtterByCategory").append(response);
						}
					});
					count++;
				}

				if (countCategory == count) {
					setTimeout(function(){	
						if (noresult != 1) {
							$('#messageError').show();
						}
						if( $(window).width() > 767 ) {
							equalheight('.equalHeight');
						}
					    $('.ui-loader').hide();
					},1000);
				}
			}
			return false;
		}
		jQuery().ready(function() {
			var signupvalidator = jQuery("#UserSignupForm").validate({
				rules: {
				   "data[Customer][first_name]": {
						required: true,
					},
					"data[Customer][last_name]": {
						required: true,
					},
		            "data[Customer][customer_email]": {
						required: true,
		                email:true,
					},
		            "data[User][password]": {
						required: true,
		                
					},
		            "data[User][confir_password]": {
						required: true,
						equalTo: '#UserPassword'
		                
					},
		            "data[Customer][customer_phone]": {
						required: true,
						number : true
					}
				},
				messages: { 
				  "data[Customer][first_name]": {
						required: "<?php echo __('Please enter firstname'); ?>",
					},
					"data[Customer][last_name]": {
					required: "<?php echo __('Please enter lastname'); ?>",
					},
		            "data[Customer][customer_email]": {
						required: "<?php echo __('Please enter email'); ?>",
						email : "<?php echo __('Please enter a valid email address'); ?>",
					},
		            "data[User][password]": {
					 	required: "<?php echo __('Please enter password'); ?>",
		                
					},
		            "data[User][confir_password]": {
						required: "<?php echo __('Please enter confirm password'); ?>",
						equalTo: "<?php echo __('Please enter the same value again'); ?>",		                
					},
		            "data[Customer][customer_phone]": {
						required: "<?php echo __('Please enter phone number'); ?>",
						number : "<?php echo __('Please enter valid phone number'); ?>",
					}
				}
			});
		    var loginvalidator = jQuery("#UserCustomerCustomerloginForm").validate({
				rules: {
					"data[User][username]": {
						required: true,
						email :true,
					},
		            "data[User][password]": {
						required: true,
					}
				},
				messages: { 
					"data[User][username]": {
						required: "<?php echo __('Please enter email'); ?>",
						email : "<?php echo __('Please enter a valid email address'); ?>",
					},
		            "data[User][password]": {
						required: "<?php echo __('Please enter password'); ?>",
					}
				}
			});
			var changepasswordvalidator = jQuery("#CustomerChangePasswordForm").validate({
				rules: {
					"data[User][oldpassword]": {
						required: true,
					},
					"data[User][newpassword]": {
						required: true,
					},
					"data[User][confirmpassword]":{
						required: true,
						equalTo: '#UserNewpassword'
					},
				},
				messages: {
					"data[User][oldpassword]": {
						required: "<?php echo __('Please enter old password'); ?>",
					},
					"data[User][newpassword]": {
						required: "<?php echo __('Please enter new password'); ?>",
					},
					"data[User][confirmpassword]":{
						required: "<?php echo __('Please enter confirm password'); ?>",
						equalTo: "<?php echo __('Please enter the same value again'); ?>",
					}
				}
			});

			var loginForgetmailvalidator = jQuery("#forgetmail").validate({
				rules: {
					"data[Users][email]": {
						required: true,
						email :true,
					}
				},
				messages: {
					"data[Users][email]": {
						required: "<?php echo __('Please enter email'); ?>",
						email : "<?php echo __('Please enter a valid email address'); ?>",
					}
				}
			});

			var Profilevalidator = jQuery("#CustomerCustomerMyaccountForm").validate({
				rules: {
					"data[Customer][first_name]": {
						required: true,
					},
		            "data[Customer][customer_phone]": {
						required: true,
		                number:true,
					}
				},
				messages: { 
					"data[Customer][first_name]": {
						required: "<?php echo __('Please enter firstname'); ?>",
					},
		            "data[Customer][customer_phone]": {
						required: "<?php echo __('Please enter phone number'); ?>",
						number : "<?php echo __('Please enter valid phone number'); ?>",
					}
				}
			});
			
			var applyvalidator = jQuery("#ApplyApplyForm").validate({
				rules: {
				   "data[Customer][first_name]": {
						required: true,
					},
					"data[Customer][last_name]": {
						required: true,
					},
		            "data[Customer][customer_email]": {
						required: true,
		                email:true,
					},
		            "data[Customer][customer_phone]": {
						required: true,
						number : true
					},
					"data[Customer][customer_city]": {
						required: true
					}
				},
				messages: { 
				  "data[Customer][first_name]": {
						required: "<?php echo __('Please enter firstname'); ?>",
					},
					"data[Customer][last_name]": {
					required: "<?php echo __('Please enter lastname'); ?>",
					},
		            "data[Customer][customer_email]": {
						required: "<?php echo __('Please enter email'); ?>",
						email : "<?php echo __('Please enter a valid email address'); ?>",
					},
		            "data[Customer][customer_phone]": {
						required: "<?php echo __('Please enter phone number'); ?>",
						number : "<?php echo __('Please enter valid phone number'); ?>",
					},
					"data[Customer][customer_city]": {
						required: "<?php echo __('Please enter cityname'); ?>",
					}
				}
			});


		    var storeValidator = jQuery("#UserStoreSignupForm").validate({
		        rules: {
		            "data[Store][store_name]": {
		                required: true,
		            },
		            "data[Store][contact_name]": {
		                required: true,
		            },
		            "data[Store][contact_phone]": {
		                required: true,
		                number:true,
		            },
		            "data[User][username]": {
		                required: true,
		                email:true,
		            },
		            "data[User][password]": {
		                required: true,
		            },
		            "data[User][confirm_password]": {
		                required: true,
		                equalTo: '#UserPassword',

		            }

		        },
		        messages: {
		            "data[Store][store_name]": {
		                required: "<?php echo __('Please enter restaurant name'); ?>",
		            },
		            "data[Store][contact_name]": {
		                required: "<?php echo __('Please enter contact name'); ?>",

		            },
		            "data[Store][contact_phone]": {
		                required: "<?php echo __('Please enter contact phone'); ?>",

		            },
		            "data[User][username]": {
		                required: "<?php echo __('Please enter email'); ?>",
		                email : "<?php echo __('Please enter a valid email address'); ?>",
		            },
		            "data[User][password]": {
		                required: "<?php echo __('Please enter password'); ?>",
		                number : "<?php echo __('Please enter valid phone number'); ?>",

		            },
		            "data[User][confirm_password]": {
		                required: "<?php echo __('Please enter confirm password'); ?>",

		            }
		        }

		    });


		    var contactUsValidator = jQuery("#ContactUsContactUsForm").validate({
		        rules: {
		            "data[ContactUs][contact_name]": {
		                required: true,
		            },
		            "data[ContactUs][contact_email]": {
		                required: true,
		                email:true,
		            },
		            "data[ContactUs][order_number]": {
		                required: true,
		            },
		            "data[ContactUs][order_date]": {
		                required: true,
		            },
		            "data[ContactUs][restaurant_name]": {
		                required: true,
		            }
		        },
		        messages: {
		            "data[ContactUs][contact_name]": {
		                required: "<?php echo __('Please enter contact name'); ?>",
		            },
		            "data[ContactUs][contact_email]": {
		                required: "<?php echo __('Please enter contact email'); ?>",
		            },
		            "data[ContactUs][order_number]": {
		                required: "<?php echo __('Please enter order number'); ?>",
		            },
		            "data[ContactUs][order_date]": {
		                required: "<?php echo __('Please enter order date'); ?>",

		            },
		            "data[ContactUs][restaurant_name]": {
		                required: "<?php echo __('Please enter restaurant name'); ?>",
		            }
		        }
		    });
		

			if (addressMode != 'Google') {
				var AddAdressBookvalidator = jQuery("#AddCustomerAddressBook").validate({
					rules: {
						"data[CustomerAddressBook][address_title]": {
							required: true,
						},
						"data[CustomerAddressBook][address]": {
							required: true,
						},
						"data[CustomerAddressBook][address_phone]": {
							required: true,
							number:true,
						},
						"data[CustomerAddressBook][landmark]": {
							required: true,
						},
						 "data[CustomerAddressBook][state_id]": {
							required: true,
						},
						 "data[CustomerAddressBook][city_id]": {
							required: true,
						},
						 "data[CustomerAddressBook][location_id]": {
							required: true,
						}
					},
					messages: {
						"data[CustomerAddressBook][address_title]": {
							required: "<?php echo __('Please enter tittle'); ?>",
						},
						"data[CustomerAddressBook][address]": {
							required: "<?php echo __('Please enter street address'); ?>",
						},
						"data[CustomerAddressBook][address_phone]": {
							required: "<?php echo __('Please enter phone number'); ?>",
						},
						"data[CustomerAddressBook][landmark]": {
							required: "<?php echo __('Please enter landmark'); ?>",
						},
						 "data[CustomerAddressBook][state_id]": {
							required: "<?php echo __('Please select state'); ?>",
						},
						 "data[CustomerAddressBook][city_id]": {
							required: "<?php echo __('Please select city'); ?>",
						},
						 "data[CustomerAddressBook][location_id]": {
							required: "<?php echo __('Please select location'); ?>",
						}
					}
				});



				var EditAdressBookvalidator = jQuery("#EditCustomerAddressBook").validate({
					rules: {
						"data[CustomerAddressBook][address_title]": {
							required: true,
						},
						"data[CustomerAddressBook][address]": {
							required: true,
						},
						"data[CustomerAddressBook][address_phone]": {
							required: true,
							number:true,
						},
						"data[CustomerAddressBook][landmark]": {
							required: true,
						},
						 "data[CustomerAddressBook][state_id]": {
							required: true,
						},
						 "data[CustomerAddressBook][city_id]": {
							required: true,
						},
						 "data[CustomerAddressBook][location_id]": {
							required: true,
						}
					},
					messages: {
						"data[CustomerAddressBook][address_title]": {
							required: "<?php echo __('Please enter tittle'); ?>",
						},
						"data[CustomerAddressBook][address]": {
							required: "<?php echo __('Please enter street address'); ?>",
						},
						"data[CustomerAddressBook][address_phone]": {
							required: "<?php echo __('Please enter phone number'); ?>",
							number : "<?php echo __('Please enter valid phone number'); ?>",
						},
						"data[CustomerAddressBook][landmark]": {
							required: "<?php echo __('Please enter landmark'); ?>",
						},
						 "data[CustomerAddressBook][state_id]": {
								required: "<?php echo __('Please select state'); ?>",
						},
						 "data[CustomerAddressBook][city_id]": {
							required: "<?php echo __('Please select city'); ?>",
						},
						 "data[CustomerAddressBook][location_id]": {
							required: "<?php echo __('Please select location'); ?>",
						}
					}
				});
			} else {
				var AddAdressBookvalidator = jQuery("#AddCustomerAddressBook").validate({
					rules: {
						"data[CustomerAddressBook][address_title]": {
							required: true,
						},
						"data[CustomerAddressBook][address_phone]": {
							required: true,
							number:true,
						},
						"data[CustomerAddressBook][google_address]": {
							required: true,
						}
					},
					messages: {
						"data[CustomerAddressBook][address_title]": {
							required: "<?php echo __('Please enter tittle'); ?>",
						},
						"data[CustomerAddressBook][address_phone]": {
							required: "<?php echo __('Please enter phone number'); ?>",
						},
						"data[CustomerAddressBook][google_address]": {
							required: "<?php echo __('Please enter address'); ?>",
						}
					}
				});
				var EditAdressBookvalidator = jQuery("#EditCustomerAddressBook").validate({
					rules: {
						"data[CustomerAddressBook][address_title]": {
							required: true,
						},
						"data[CustomerAddressBook][address_phone]": {
							required: true,
							number:true,
						},
						"data[CustomerAddressBook][google_address]": {
							required: true,
						},
					},
					messages: {
						"data[CustomerAddressBook][address_title]": {
							required: "<?php echo __('Please enter tittle'); ?>",
						},
						"data[CustomerAddressBook][address_phone]": {
							required: "<?php echo __('Please enter phone number'); ?>",
							number : "<?php echo __('Please enter valid phone number'); ?>",
						},
						"data[CustomerAddressBook][google_address]": {
							required: "<?php echo __('Please enter address'); ?>",
						},
					}
				});
			}
		});
		function changeCustomerEmail() {
			var CustomerCustomerEmail = $.trim($("#CustomerCustomerEmail").val());
			$('#userMailError').html('');
			if(CustomerCustomerEmail == ''){		        
				$("#userMailError").html("<?php echo __('Please enter email'); ?>");
				$("#CustomerCustomerEmail").focus();
				return false;
			} else if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(CustomerCustomerEmail))){
		    	$('#userMailError').html("<?php echo __('Please enter the valid email'); ?>");
		    	$('#CustomerCustomerEmail').focus();
		    	return false;
		    } else {
			    var line = 'Are you sure want to change your email if continue your current session will be signout automatically ?';
			    if (confirm(line)) {
			        //$('#CustomerChangeCustomerEmailForm').submit();
			    } else {
			        return false;
			    }
			}			
		}	
		function validateBook() {

		    var BookaTableGuestCount    = $.trim($("#BookaTableGuestCount").val());
		    var BookaTableBookingDate   = $.trim($("#BookaTableBookingDate").val());
		    var BookaTableBookingTime   = $.trim($("#BookaTableBookingTime").val());
		    var BookaTableCustomerName  = $.trim($("#BookaTableCustomerName").val());
		    var BookaTableBookingEmail  = $.trim($("#BookaTableBookingEmail").val());
		    var BookaTableBookingPhone  = $.trim($("#BookaTableBookingPhone").val());
		    var emailRegex              = new RegExp(/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$/i);

		    $("#bookError").hide();
		    $('#bookError').html('');

		    if(BookaTableGuestCount == ''){
		        $("#bookError").show();
		        $("#bookError").html("<?php echo __('Please select guest count'); ?>");
		        $("#BookaTableGuestCount").focus();
		        return false;
		    } else if(BookaTableBookingDate == ''){
		        $("#bookError").show();
		        $("#bookError").html("<?php echo __('Please select booking date'); ?>");
		        $("#BookaTableBookingDate").focus();
		        return false;
		    } else if(BookaTableBookingTime == ''){
		        $("#bookError").show();
		        $("#bookError").html("<?php echo __('Please select booking time'); ?>");
		        $("#BookaTableBookingTime").focus();
		        return false;
		    } else if(BookaTableCustomerName == ''){
		        $("#bookError").show();
		        $("#bookError").html("<?php echo __('Please enter lastname'); ?>");
		        $("#BookaTableCustomerName").focus();
		        return false;
		    } else if(BookaTableBookingEmail == ''){
		        $("#bookError").show();
		        $("#bookError").html("<?php echo __('Please enter email'); ?>");		        
		        $("#BookaTableBookingEmail").focus();
		        return false;
		    } else if (!emailRegex.test(BookaTableBookingEmail)) {
		        $("#bookError").show();
		        $("#bookError").html("<?php echo __('Please enter a valid email address'); ?>");
		        $("#BookaTableBookingEmail").focus();
		        return false;
		    } else if(BookaTableBookingPhone == ''){
		        $("#bookError").show();
		        $("#bookError").html("<?php echo __('Please enter phone number'); ?>");
		        $("#BookaTableBookingPhone").focus();
		        return false;
		    } else if(isNaN(BookaTableBookingPhone)){
		        $("#bookError").show();
		        $("#bookError").html("<?php echo __('Please enter valid phone number'); ?>");
		        $("#BookaTableBookingPhone").focus();
		        return false;
		    } else {

		        var formData = ($("#BookaTableStoreitemsForm").serialize());
		        $.post(rp+'/BookaTables/bookaTable/',{'formData':formData}, function(res) {
		            $(".bookRequest").find(".bookaTableSuccess").show();
		            $(".bookRequest").find('input[type=text],textarea,select').val('');
		            setTimeout(function(){
		                $(".bookRequest").find(".bookaTableSuccess").hide();
		                $('#book_a_table').modal('hide');
		            },3000);

		        });
		    }
		    return false;
		}	
		function validateAddress () {
			var title     = $('#CustomerAddressBookAddressTitles').val();
			var id        = $('#CustomerAddressBookId').val();
			var street    = $('#street').val();
			var ph        = $('#phone').val();
			var bulinding = $('#build').val();
			var state     = $('#CustomerAddressBookStateIds').val();
			var city      = $('#CustomerAddressBookCityIds').val();
			var area      = $('#CustomerAddressBookLocationIds').val();
			$('.error').html('');			
			if(title == '') {
				$('.titleerorr').html("Entrez votre titre svp");
				$('#CustomerAddressBookAddressTitles').focus();
				return false;
			}else if(street == ''){
				$('.streeterorr').html("<?php echo __('Please enter street address'); ?>");
				$('#street').focus();
				return false;
			}else if(ph == ''){
				$('.phoneerorr').html("<?php echo __('Please enter phone number'); ?>");
				$('#phone').focus();
				return false;
			}else if(isNaN(ph)){
				$('.phoneerorr').html("<?php echo __('Please enter valid phone number'); ?>");
				$('#phone').focus();
				return false;
			}else if(bulinding == ''){
				$('.builderorr').html("<?php echo __('Please enter landmark'); ?>");
				$('#build').focus();
				return false;
			}else if(state == ''){
				$('.stateerorr').html("<?php echo __('Please select state'); ?>");
				$('#CustomerAddressBookStateIds').focus();
				return false;
			}else if(city == ''){
				$('.cityerorr').html("<?php echo __('Please select city'); ?>");
				$('#CustomerAddressBookCityIds').focus();
				return false;
			}else if(area == ''){
				$('.areaerorr').html("<?php echo __('Please select location'); ?>");
				$('#CustomerAddressBookLocationIds').focus();
				return false;
			}else {
				$.post(rp+'customer/customers/editaddresschecking',{'title':title,'id':id}, function(response) {
					if($.trim(response) == 'success'){
						$("#EditCustomerAddressBook").submit();
					} else {
						$('.checkerorr').html("<?php echo __('Addressbook title already exists'); ?>");
						return false;
					}
				});
			}
			return false;
		}
		function addAddressCheck () {
			var title     = $('#CustomerAddressBookAddressTitle').val();
			$('.checkAdderorr').show();
			$('.checkAdderorr').html('');
			if (title != '') {
				$.post(rp+'customer/customers/editaddresschecking',{'title':title}, function(response) {
					if($.trim(response) == 'success'){
						$("#AddCustomerAddressBook").submit();
					} else {
						$('.checkAdderorr').html("<?php echo __('Addressbook title already exists'); ?>");
						return false;
					}
				});
				return false;
			}
		}
		function checking(){
			$('.passerror').html('');
			var pass     = $('#UserOldpassword').val();
			var newpass  = $('#UserNewpassword').val();
			var confirm  = $('#UserConfirmpassword').val();
			if(pass != '' && newpass == '' && confirm == '' ) {
				$.post(rp + 'customer/Customers/passchecking', {'pass': pass}, function (response) {
					if ($.trim(response) == 'sucess') {
						$('#CustomerChangePasswordForm').submit();
					} else {
						$('#UserOldpassword').focus();
						$('.passerror').html("<?php echo __('Please enter old password'); ?>");
					}
				});
			} else {
				$('#UserOldpassword').focus();
				$('.passerror').html("<?php echo __('Please enter old password'); ?>");
				return false;
			}
		}
		function saveCard () {
			//Stripe.setPublishableKey('pk_test_o2yvGW5u0AxIAazkU7b0JKwr');
			Stripe.setPublishableKey(publishKey);
			var CardName	= $('#CardName').val();
		 	var CardNumber	= $('#CardNumber').val();
		  	var CardCvv		= $('#CardCvv').val();
		    var noRecord    = $('#noRecord').text();
		    var savedCard 	= $.trim($('[name="data[Card][Saved]"]:checked').val());		    
		    var savecard 	= $('.savecard').prop('checked');	
		    if ($('#cardCheck').is(":checked")) {
		        if (noRecord == '') {
		            if (savedCard != '') {
		                $('#UserPaymentForm').submit();
		                return false;
		            } else {
		                $('#error').html("<?php echo __('Please select any card'); ?>");
		                $('#error').addClass('error');
		                return false;
		            }
		        } else {
		            $('#error').html("<?php echo __('Card is not available'); ?>");
		            $('#error').addClass('error');
		            return false;
		        }
		    }   

		  	$('#error').html('');
		  	$('#error').removeClass('error');
		  	if (CardName == '') {
		        $('#error').html("<?php echo __('Please enter the name'); ?>");
		        $('#error').addClass('error');
		        $('#CardName').focus();
		        return false;		    
		  	} else if (CardNumber == '') {
		        $('#error').html("<?php echo __('Please enter the card number'); ?>");
		        $('#error').addClass('error');
		        $('#CardNumber').focus();
		        return false;
		  	} else if (CardCvv == '') {
		        $('#error').html("<?php echo __('Please enter the cvv'); ?>");
		        $('#error').addClass('error');
		        $('#CardCvv').focus();
		        return false;
		  	} else {
				Stripe.card.createToken($('[name=StripeForm]'), 
			    function(status, response){			       
			        if (status == 200 && response.id != '') {
						$('#CardName').append($('<input type="text" name="data[StripeCustomer][stripe_token_id]" value="'+response.id+'" />'+
												'<input type="text" name="data[StripeCustomer][card_id]" value="'+response.card.id+'" />'+
												'<input type="text" name="data[StripeCustomer][card_number]" value="'+response.card.last4+'" />'+
												'<input type="text" name="data[StripeCustomer][card_brand]" value="'+response.card.brand+'" />'+
												'<input type="text" name="data[StripeCustomer][card_type]" value="'+response.card.funding+'" />'+
												'<input type="text" name="data[StripeCustomer][exp_month]" value="'+response.card.exp_month+'" />'+
												'<input type="text" name="data[StripeCustomer][exp_year]" value="'+response.card.exp_year+'" />'+
												'<input type="text" name="data[StripeCustomer][country]" value="'+response.card.country+'" />'+
												'<input type="text" name="data[StripeCustomer][customer_name]" value="'+response.card.name+'" />'+
												'<input type="text" name="data[StripeCustomer][client_ip]" value="'+response.client_ip+'" />'));						
		        		$("#stripebtn").attr('disabled','disabled');
		        		var checkMe = $('#checkout').val();
		        		if (checkMe == 'checkout') {

		        			if($.trim(savecard) == 'true') {
	        					var formData = ($("#checkoutOrder").serialize());
				        		$.post(rp+'/checkouts/customerCardAdd/',{'formData':formData}, function(res) {
				        			$('#stripepayment_id').val(res);
						            $('#checkoutOrder').submit();
						        });
							} else {
		        				$('#stripetoken').val(response.id);
		        				$('#checkoutOrder').submit();
		        			}

		        			/*if($.trim(savecard) == 'true') {
		        				var formData = ($("#UserIndexForm").serialize());
				        		$.post(rp+'/checkouts/customerCardAdd/',{'formData':formData}, function(res) {
						            $('#addpayment').modal('hide');
						            var payment 	 = $('.orderGrandTotal').val();
						            $.post(rp+'/checkouts/paymentCard/',{'payment':payment}, function(respon) {
						            	// $('#payment').html(respon);
						            	$('#paymentLoad').html(respon);
							        });
							        $.post(rp+'/checkouts/cardAdd/', function(response) {
						            	$('#addpayment').html(response);
							        });
						        });
		        			} else {
		        				$('#stripetoken').val(response.id);
		        				$('#checkoutOrder').submit();
		        			}*/
		        			
			        	} else {
			        		$('#UserIndexForm').submit();
			        	}
			        } else {
			        	alert("<?php echo __('Check your card details'); ?>");
			        }
				});
		    	return false;
			}
		}


		function saveCardDetails () {
			//Stripe.setPublishableKey('pk_test_o2yvGW5u0AxIAazkU7b0JKwr');
			Stripe.setPublishableKey(publishKey);
			var CardName	= $('#CardName').val();
		 	var CardNumber	= $('#CardNumber').val();
		  	var CardCvv		= $('#CardCvv').val();
		    var noRecord    = $('#noRecord').text();
		    var savedCard = $.trim($('[name="data[Card][Saved]"]:checked').val());
		    var savecard 	= $('.savecard').prop('checked');		

		    if ($('#cardCheck').is(":checked")) {
		        if (noRecord == '') {
		            if (savedCard != '') {
		                $('#UserPaymentForm').submit();
		                return false;
		            } else {
		                $('#error').html("<?php echo __('Please select any card'); ?>");
		                $('#error').addClass('error');
		                return false;
		            }
		        } else {
		            $('#error').html("<?php echo __('Card is not available'); ?>");
		            $('#error').addClass('error');
		            return false;
		        }
		    }   

		  	$('#error').html('');
		  	$('#error').removeClass('error');
		  	if (CardName == '') {
		        $('#error').html("<?php echo __('Please enter the name'); ?>");
		        $('#error').addClass('error');
		        $('#CardName').focus();
		        return false;		    
		  	} else if (CardNumber == '') {
		        $('#error').html("<?php echo __('Please enter the card number'); ?>");
		        $('#error').addClass('error');
		        $('#CardNumber').focus();
		        return false;
		  	} else if (CardCvv == '') {
		        $('#error').html("<?php echo __('Please enter the cvv'); ?>");
		        $('#error').addClass('error');
		        $('#CardCvv').focus();
		        return false;
		  	} else {
				Stripe.card.createToken($('[name=OrderForm]'), 
			    function(status, response){	    
			        if (status == 200 && response.id != '') {
						$('#CardName').append($('<input type="text" name="data[StripeCustomer][stripe_token_id]" value="'+response.id+'" />'+
												'<input type="text" name="data[StripeCustomer][card_id]" value="'+response.card.id+'" />'+
												'<input type="text" name="data[StripeCustomer][card_number]" value="'+response.card.last4+'" />'+
												'<input type="text" name="data[StripeCustomer][card_brand]" value="'+response.card.brand+'" />'+
												'<input type="text" name="data[StripeCustomer][card_type]" value="'+response.card.funding+'" />'+
												'<input type="text" name="data[StripeCustomer][exp_month]" value="'+response.card.exp_month+'" />'+
												'<input type="text" name="data[StripeCustomer][exp_year]" value="'+response.card.exp_year+'" />'+
												'<input type="text" name="data[StripeCustomer][country]" value="'+response.card.country+'" />'+
												'<input type="text" name="data[StripeCustomer][customer_name]" value="'+response.card.name+'" />'+
												'<input type="text" name="data[StripeCustomer][client_ip]" value="'+response.client_ip+'" />'));						
		        		// $("#stripebtn").attr('disabled','disabled');
		        		var checkMe = $('#checkout').val();
		        		if (checkMe == 'checkout') {		        			
		        			if($.trim(savecard) == 'true') {
	        					var formData = ($("#checkoutOrder").serialize());
				        		$.post(rp+'/checkouts/customerCardAdd/',{'formData':formData}, function(res) {
				        			$('#stripepayment_id').val(res);
						            $('#checkoutOrder').submit();
						            $("#orderbtn").attr('disabled','disabled');
						        });
							} else {
		        				$('#stripetoken').val(response.id);
		        				$('#checkoutOrder').submit();
		        				$("#orderbtn").attr('disabled','disabled');
		        			}		        			
			        	} else {
			        		$('#UserIndexForm').submit();
			        	}
			        } else {
			        	alert("<?php echo __('Check your card details'); ?>");
			        }
				});
		    	return false;
			}
		}

		// WaletAmount

		function walletCard() {
			
			var CustomerWalletAmount 	= $('#CustomerWalletAmount').val();
			var WalletCardName			= $('#WalletCardName').val();
		 	var WalletCardNumber		= $('#WalletCardNumber').val();
		  	var WalletCardCvv			= $('#WalletCardCvv').val();
		    var noRecord    			= $('#noRecord').text();
		    var CustomerWalletCardType	= $('#CustomerWalletCardType').val();

		    $('.error').html("");
		    
		    if (CustomerWalletAmount == '') {
		    	$('#Amount').html("<?php echo __('Please enter the amount'); ?>");
		        $('#CustomerWalletAmount').focus();
		        return false;
		    } else if (isNaN(CustomerWalletAmount) || CustomerWalletAmount == 0 || CustomerWalletAmount < 0.5) {
		    	$('#Amount').html("<?php echo __('Please enter the valid amount'); ?>");
		        $('#CustomerWalletAmount').focus();
		        return false;
		    }

		    if (CustomerWalletCardType == 'savedCard') {

		    	var savedCard = $.trim($('[name="data[Customer][walletCard]"]:checked').val());

		    	if (savedCard != '') {
	                $('#AddAmountWallet').submit();
	            } else {
	                $('#Amount').html("<?php echo __('Please select one card'); ?>");
	                return false;
	            }

		    } else {

			  	$('#walletError').html('');
			  	$('#walletError').removeClass('error');

			  	if (WalletCardName == '') {

			        $('#walletError').html("<?php echo __('Please enter the name'); ?>");
			        $('#walletError').addClass('error');
			        $('#WalletCardName').focus();
			        return false;
			    
			  	} else if (WalletCardNumber == '') {

			        $('#walletError').html("<?php echo __('Please enter the card number'); ?>");
			        $('#walletError').addClass('error');
			        $('#WalletCardNumber').focus();
			        return false;

			  	} else if (WalletCardCvv == '') {

			        $('#walletError').html("<?php echo __('Please enter the cvv'); ?>");
			        $('#walletError').addClass('error');
			        $('#WalletCardCvv').focus();
			        return false;

			  	} else {
			  		Stripe.setPublishableKey(publishKey);

					Stripe.card.createToken($('[name=StripeWalletForm]'), 
				    function(status, response){
				       
				        if (status == 200 && response.id != '') {

							$('#paymentWallet').append($('<input type="hidden" name="data[StripeCustomer][stripe_token_id]" value="'+response.id+'" />'+
													'<input type="hidden" name="data[StripeCustomer][card_id]" value="'+response.card.id+'" />'+
													'<input type="hidden" name="data[StripeCustomer][card_number]" value="'+response.card.last4+'" />'+
													'<input type="hidden" name="data[StripeCustomer][card_brand]" value="'+response.card.brand+'" />'+
													'<input type="hidden" name="data[StripeCustomer][card_type]" value="'+response.card.funding+'" />'+
													'<input type="hidden" name="data[StripeCustomer][exp_month]" value="'+response.card.exp_month+'" />'+
													'<input type="hidden" name="data[StripeCustomer][exp_year]" value="'+response.card.exp_year+'" />'+
													'<input type="hidden" name="data[StripeCustomer][country]" value="'+response.card.country+'" />'+
													'<input type="hidden" name="data[StripeCustomer][customer_name]" value="'+response.card.name+'" />'+
													'<input type="hidden" name="data[StripeCustomer][client_ip]" value="'+response.client_ip+'" />'));

							
			        		$("#paymentBtn").attr('disabled','disabled');

				        	$('#AddAmountWallet').submit();

				        } else {
				        	alert("<?php echo __('Check your card details'); ?>");
				        }
					});
			    	return false;
				}
			}
		} <?php
		if (!empty($loggedUser)) { ?>
			//Pusher Notification
		    // var pusher   = new Pusher('<?php echo $pusherKey; ?>');
		    // var channel  = pusher.subscribe('FoodCustomer_<?php echo $loggedUser['Customer']['id']; ?>');
		    // var notifier = new PusherNotifier(channel); 
		    <?php
		}


		if (strtolower($this->request->params['controller']) == 'customers' &&
			strtolower($this->request->params['action']) == 'customer_myaccount') { ?>
				$(document).ready(function() {
					updateOrderMap();
				}); <?php
		}	
		if ($this->request->params['controller'] == 'searches' &&
			$this->request->params['action'] == 'storeitems') { ?>
			$(document).ready(function(){								
				$(".viewCart_mobile").click(function() {
					$('#cart-sidebar').show();
				});
				$(".mobile_cart_close").click(function() {
					$('#cart-sidebar').hide();
				});
			}); <?php
		} ?>

		if (window.location==top.location) {
			console.log('Window');
		} else {

			$('.siteLink').hide();
			$('.pluginLogo').hide();
			$('.pluginFooter').hide();
			$('.pluginResIcon').show();
			$('.pluginResLink').show();
			console.log('Iframe');
		}


		</script> 
		<script type="text/javascript">
			$(document).ready(function(){
			    $(".baricon").click(function(){
			       $(".mobilemenu").slideToggle(1000);
			    });
				$(".faq_cont_border h3").click(function(){
					$(".faq_cont_border h3").removeClass("active");
					$(this).addClass("active");
					$(".faq_cont_border .faqtoggle").hide();
					$(this).next(".faqtoggle").show();
				});
			});
		</script>
		<?php
		if (!empty($siteSetting['Sitesetting']['google_analytics'])) {
			echo '<script>'. $siteSetting['Sitesetting']['google_analytics']. '</script>';
		}
		if (!empty($siteSetting['Sitesetting']['woopra_analytics'])) {
			echo '<script>'. $siteSetting['Sitesetting']['woopra_analytics']. '</script>';
		} ?>
	</body>
</html>