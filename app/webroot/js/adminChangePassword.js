jQuery(function() {

  var login = jQuery("#UserAdminLoginForm").validate({
    rules: {     
        "data[User][username]": {
        required: true,
      },
      "data[User][password]": {
        required: true,
      }
    },
    messages: { 
      "data[User][username]": {
        required: "Entrez votre nom utilisateur svp",
      },
      "data[User][password]": {
        required: "Entrez le mot de passe",
      },
    }
  });

  var forgetmail = jQuery("#forgetmail").validate({
    rules: {
      "data[Users][email]": {
        required: true,
        email : true
      }
    },
    messages: {
      "data[Users][email]": {
        required: "Entrez un email SVP",
      },
    }
  });

  var paymentAddvalidator = jQuery("#SitesettingAdminPaymentSettingForm").validate({
    rules: {
      "data[Sitesetting][stripe_url]": {
        required: true,
      },
      "data[Sitesetting][stripe_ac]": {
        required: true,
      }
    },
    messages: { 
      "data[Sitesetting][stripe_url]": {
        required: "Please Enter Url detail",
      },
      "data[Sitesetting][stripe_ac]": {
        required: "Please Enter the account detail",
      }

    }
  });

  var UserStoreStoreLoginForm = jQuery("#UserStoreStoreLoginForm").validate({
      rules: {     
          "data[User][username]": {
          required: true,
        },
        "data[User][password]": {
          required: true,
        }
      },
      messages: { 
        "data[User][username]": {
          required: "Entrez votre nom utilisateur svp",
        },
        "data[User][password]": {
          required: "Entrez le mot de passe",
        },
      }
  });

  var ProductAdminIndexForm = jQuery("#ProductAdminIndexForm").validate({
    rules: {
      "data[Product][store_id]": {
        required: true,
      },
      "data[excel]": {
        required: true,
      }
    },
    messages: { 
      "data[Product][store_id]": {
        required: "Sélectionnez le restaurant svp",
      },
      "data[excel]": {
        required: "Please select xls file",
      }

    }
  });


  var CategoryAddvalidator = jQuery("#CategoryAdminAddForm").validate({
    rules: {
      "data[Category][category_name]": {
        required: true,
      }
    },
    messages: { 
      "data[Category][category_name]": {
        required: "Entrez le nom du Catégorie svp",
      }

    }
  });

  var CategoryEditvalidator = jQuery("#CategoryAdminEditForm").validate({
    rules: {
      "data[Category][category_name]": {
        required: true,
      }
    },
    messages: { 
      "data[Category][category_name]": {
        required: "Entrez le nom du Catégorie svp",
      }

    }
  });

  var CuisineAddvalidator = jQuery("#CuisineAdminAddForm").validate({
    rules: {
      "data[Cuisine][cuisine_name]": {
        required: true,
      }
    },
    messages: { 
      "data[Cuisine][cuisine_name]": {
        required: "Entrez le nom de la spécialité culinaire svp",
      }

    }
  });


  var CuisineEditvalidator = jQuery("#CuisineAdminEditForm").validate({
    rules: {
      "data[Cuisine][cuisine_name]": {
        required: true,
      }
    },
    messages: { 
      "data[Cuisine][cuisine_name]": {
        required: "Entrez le nom de la spécialité culinaire svp",
      }

    }
  });

  var UserAdminAddvalidator = jQuery("#UserAdminAddForm").validate({
    rules: {
      "data[Customer][first_name]": {
        required: true,
      },
      "data[Customer][last_name]": {
        required: true,
      },
      "data[Customer][customer_phone]": {
        required: true,
        number:true,
      },
      "data[Customer][customer_email]": {
        required: true,
        email:true,
      },
      "data[User][password]": {
        required: true,
      }
    },
    messages: { 
      "data[Customer][first_name]": {
        required: "Entrez votre prénom SVP",
      },
      "data[Customer][last_name]": {
        required: "Entrez votre nom svp",
      },
      "data[Customer][customer_phone]": {
        required: "Entrez un numéro svp",
      },
      "data[Customer][customer_email]": {
        required: "Entrez un email SVP",
      },
      "data[User][password]": {
        required: "Entrez le mot de passe",
      }
    }
  });

  var UserAdminEditvalidator = jQuery("#UserAdminEditForm").validate({
    rules: {
      "data[Customer][first_name]": {
        required: true,
      },
      "data[Customer][last_name]": {
        required: true,
      },
      "data[Customer][customer_phone]": {
        required: true,
        number:true,
      },
      "data[Customer][customer_email]": {
        required: true,
        email:true,
      },
      "data[User][password]": {
        required: true,
      }
    },
    messages: { 
      "data[Customer][first_name]": {
        required: "Entrez votre prénom SVP",
      },
      "data[Customer][last_name]": {
        required: "Entrez votre nom svp",
      },
      "data[Customer][customer_phone]": {
        required: "Entrez un numéro svp",
      },
      "data[Customer][customer_email]": {
        required: "Entrez un email SVP",
      },
      "data[User][password]": {
        required: "Entrez le mot de passe",
      }
    }
  });

    if (addressMode != 'Google') {

        var CustomerBookvalidator = jQuery("#EditCustomerAddressBook").validate({
            rules: {
                "data[CustomerAddressBook][address_title]": {
                    required: true,
                },
                "data[CustomerAddressBook][address_phone]": {
                    required: true,
                    number: true,
                },
                "data[CustomerAddressBook][landmark]": {
                    required: true,
                },
                "data[CustomerAddressBook][address]": {
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
                    required: "Entrez votre titre svp",
                },
                "data[CustomerAddressBook][address_phone]": {
                    required: "Entrez un numéro svp",
                },
                "data[CustomerAddressBook][landmark]": {
                    required: "Please Enter the landmark",
                },
                "data[CustomerAddressBook][address]": {
                    required: "Entrez une adresse SVP",
                },
                "data[CustomerAddressBook][city_id]": {
                    required: "Sélectionner la ville SVP",
                },
                "data[CustomerAddressBook][location_id]": {
                    required: "Please select the location",
                }
            }
        });
    } else {
        var CustomerBookvalidator = jQuery("#EditCustomerAddressBook").validate({
            rules: {
                "data[CustomerAddressBook][address_title]": {
                    required: true,
                },
                "data[CustomerAddressBook][address_phone]": {
                    required: true,
                    number: true,
                },
                "data[CustomerAddressBook][google_address]": {
                    required: true,
                }
            },
            messages: {
                "data[CustomerAddressBook][address_title]": {
                    required: "Entrez votre titre svp",
                },
                "data[CustomerAddressBook][address_phone]": {
                    required: "Entrez un numéro svp",
                },
                "data[CustomerAddressBook][google_address]": {
                    required: "Entrez une adresse SVP",
                }
            }
        });
    }
    
  /*var ProductAddvalidator = jQuery("#ProductAdminAddForm").validate({
      rules: {
        "data[Product][store_id]": {
          required: true,
        },
        "data[Product][product_name]": {
          required: true,
        },
        "data[Product][category_id]": {
          required: true,
          
        },
        "data[product_image][]": {
          required: true,
        },
             
      },
      messages: { 
        "data[Product][store_id]": {
          required: "Please Select the store",
        },
        "data[Product][product_name]": {
          required: "Please Enter the Menu Name",
        },
        "data[Product][category_id]": {
          required: "Please Select the category",
        },
        "data[product_image][]": {
            required: "Please select image",
        },
      }
  });

  var ProductEditvalidator = jQuery("#ProductAdminEditForm").validate({
    rules: {
      "data[Product][store_id]": {
        required: true,
      },
      "data[Product][product_name]": {
        required: true,
      },
      "data[Product][category_id]": {
        required: true,
        
      },
      "data[product_image][]": {
        required: true,
      },
           
    },
    messages: {
      "data[Product][store_id]": {
        required: "Please Select the store",
      },
      "data[Product][product_name]": {
        required: "Please Enter the Menu Name",
      },
      "data[Product][category_id]": {
        required: "Please Select the category",
      },
      "data[product_image][]": {
          required: "Please select image",
      },
    }
  });*/
    
    var VoucherAddvalidator = jQuery("#form-username").validate({
    rules: {
      "data[Voucher][voucher_code]": {
        required: true,
      },
      "data[Voucher][type_offer]": {
        required: true,
        
      },
      "data[Voucher][offer_mode]": {
        required: true,
        
      },
             "data[Voucher][offer_value]": {
        required: true,
                number:true,
      },
            "data[Voucher][from_date]": {
        required: true,
      },
            "data[Voucher][to_date]": {
        required: true,
      }
           
    },

    errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        
    messages: { 
          "data[Voucher][voucher_code]": {
            required: "Please Enter Code",
          },
          "data[Voucher][type_offer]": {
           required: "Please Enter offertype",
            
          },
          "data[Voucher][offer_mode]": {
          required: "Please Enter offermode",
            
          },
                 "data[Voucher][offer_value]": {
            required: "Please Enter offervalue",
          },
                "data[Voucher][from_date]": {
            required: "Entrez la date de début svp",
          },
                "data[Voucher][to_date]": {
            required: "Entrez la date de fin svp",
          }
            }
  });
    
     var VoucherEditvalidator = jQuery("#form-username1").validate({
    rules: {
      "data[Voucher][voucher_code]": {
        required: true,
      },
      "data[Voucher][type_offer]": {
        required: true,
        
      },
      "data[Voucher][offer_mode]": {
        required: true,
        
      },
             "data[Voucher][offer_value]": {
        required: true,
                number:true,
      },
            "data[Voucher][from_date]": {
        required: true,
      },
            "data[Voucher][to_date]": {
        required: true,
      }
           
    },
    messages: { 
          "data[Voucher][voucher_code]": {
            required: "Please Enter Code",
          },
          "data[Voucher][type_offer]": {
           required: "Please Enter offertype",
            
          },
          "data[Voucher][offer_mode]": {
          required: "Please Enter offermode",
            
          },
                 "data[Voucher][offer_value]": {
            required: "Please Enter offervalue",
          },
                "data[Voucher][from_date]": {
            required: "Entrez la date de début svp",
          },
                "data[Voucher][to_date]": {
            required: "Entrez la date de fin svp",
          }
            }
  });
    
    
    var StoreofferAdminAddForm = jQuery("#StoreofferAdminAddForm").validate({
    rules: {
      "data[Storeoffer][store_id]": {
        required: true,
      },
      "data[Storeoffer][offer_percentage]": {
        required: true,
                number:true,
                min:1,
                max:99,
        
      },
      "data[Storeoffer][offer_price]": {
        required: true,
                number:true,
                min:1
        
      },
             "data[Storeoffer][from_date]": {
        required: true,
                
      },
            "data[Storeoffer][to_date]": {
        required: true,
      }
           
    },
    messages: { 
          "data[Storeoffer][store_id]": {
        required: "Sélectionnez le restaurant svp",
      },
      "data[Storeoffer][offer_percentage]": {
        required: "Entrez le % de réduction svp",
        
      },
      "data[Storeoffer][offer_price]": {
           required: "Veuillez entrer le prix de vente",
        
      },
             "data[Storeoffer][from_date]": {
        required: "Entrez la date de début svp",
      },
            "data[Storeoffer][to_date]": {
        required: "Entrez la date de fin svp",
      }
    }
           
  });
    
    
    var StoreOfferEditvalidator = jQuery("#form-storeofferEdit").validate({
    rules: {
      "data[Storeoffer][store_id]": {
        required: true,
      },
      "data[Storeoffer][offer_percentage]": {
        required: true,
                number:true,
                min:1,
                max:99,
        
      },
      "data[Storeoffer][offer_price]": {
        required: true,
                number:true,
                min:1
        
      },
             "data[Storeoffer][from_date]": {
        required: true,
                
      },
            "data[Storeoffer][to_date]": {
        required: true,
      }
           
    },
    messages: { 
          "data[Storeoffer][store_id]": {
        required: "Sélectionnez le restaurant svp",
      },
      "data[Storeoffer][offer_percentage]": {
        required: "Entrez le % de réduction svp",
        
      },
      "data[Storeoffer][offer_price]": {
           required: "Veuillez entrer le prix de vente",
        
      },
             "data[Storeoffer][from_date]": {
        required: "Entrez la date de début svp",
      },
            "data[Storeoffer][to_date]": {
        required: "Entrez la date de fin svp",
      }
      }
           
  });

  var dealAddValidator = jQuery("#DealAdminAddForm").validate({
    rules: {
      "data[Deal][store_id]": {
        required: true,
      },
      "data[Deal][deal_name]": {
        required: true,
        
      },
      "data[Deal][main_product]": {
        required: true,
        
      },
      "data[Deal][sub_product]": {
        required: true,
                
      }
           
    },
    messages: { 
      "data[Deal][store_id]": {
        required: "Sélectionnez le restaurant svp",
      },
      "data[Deal][deal_name]": {
        required: "Entrez le deal nom svp",
        
      },
      "data[Deal][main_product]": {
           required: "Entrez le nom du produit svp",
        
      },
      "data[Deal][sub_product]": {
        required: "Entrez le nom du produit svp",
      }
    }
           
  });

  var dealEditValidator = jQuery("#DealAdminEditForm").validate({
    rules: {
      "data[Deal][store_id]": {
        required: true,
      },
      "data[Deal][deal_name]": {
        required: true,
        
      },
      "data[Deal][main_product]": {
        required: true,
        
      },
      "data[Deal][sub_product]": {
        required: true,      
      }
           
    },
    messages: { 
          "data[Deal][store_id]": {
        required: "Sélectionnez le restaurant svp",
      },
      "data[Deal][deal_name]": {
        required: "Entrez le deal nom svp",
        
      },
      "data[Deal][main_product]": {
           required: "Entrez le nom du produit svp",
        
      },
             "data[Deal][sub_product]": {
        required: "Entrez le nom du produit svp",
      }
    }
           
  });


  var newsletterSelectValidator = jQuery("#NewsletterAdminSendselectcustomerForm").validate({
    rules: {
      "data[Newsletter][subject]": {
        required: true,
      },
      "data[Newsletter][to]": {
        required: true,
        
      }
           
    },
    messages: { 
        "data[Newsletter][subject]": {
        required: "Please enter subject",
      },
      "data[Newsletter][to]": {
        required: "Please enter to address",
        
      },
      }
  });

  var changepasswordValidator = jQuery("#userAdminChangePasswordForm").validate({
    rules: {
      "data[user][new_pass]": {
        required: true,
      },
      "data[user][confirm_pass]": {
        required: true,
        equalTo: '#userNewPass',
        
      }
           
    },
    messages: { 
          "data[user][new_pass]": {
        required: "Entrez le mot de passe",
      },
      "data[user][confirm_pass]": {
        required: "Confirmez votre mot de passe svp",
        
      }
    }
           
  });

  var newsletterAllValidator = jQuery("#NewsletterAdminSendallForm").validate({
    rules: {
      "data[Newsletter][subject]": {
        required: true,
      },
      "data[Newsletter][to]": {
        required: true,
      }
           
    },
    messages: { 
        "data[Newsletter][subject]": {
        required: "Please enter subject",
      },
      "data[Newsletter][to]": {
        required: "Please enter to address",
      },
      }
  });

  var DriverAdminAddForm = jQuery("#DriverAdminAddForm").validate({
    rules: {
      "data[Driver][driver_name]": {
        required: true,
      },
      "data[Driver][driver_email]": {
        required: true,
        email:true,
      },
      "data[User][username]": {
        required: true,
        number:true,
      },
      "data[User][password]": {
        required: true,
      },
      "data[User][conformpassword]": {
        required: true,
        equalTo:'#UserPassword',
      },
      "data[Driver][address]": {
        required: true,
      },
      "data[Driver][license_no]": {
        required: true,
      },
    },
    messages: { 
      "data[Driver][driver_name]": {
        required: "Veuillez entrer le nom du coursier",
      },
      "data[Driver][driver_email]": {
        required: "Entrez un email SVP",
      },
      "data[User][username]": {
        required: "Entrez votre numéro de téléphone SVP",
      },
      "data[User][password]": {
        required: "Entrez le mot de passe",
      },
      "data[User][conformpassword]": {
        required: "Confirmer le mot de passe SVP",
      },
      "data[Driver][address]": {
        required: "Entrez une adresse SVP",
      },
      "data[Driver][license_no]": {
        required: "Veuillez entrer le n° de permis",
      },
    }
  });
  

  var DriverAdminEditForm = jQuery("#DriverAdminEditForm").validate({
    rules: {
      "data[Driver][driver_name]": {
        required: true,
      },
      "data[Driver][driver_email]": {
        required: true,
        email:true,
      },
      "data[Driver][driver_phone]": {
        required: true,
        number : true
      },
      "data[Driver][address]": {
        required: true,
      },
      "data[Driver][license_no]": {
        required: true,
      },
    },
    messages: { 
      "data[Driver][driver_name]": {
        required: "Veuillez entrer le nom du coursier",
      },
      "data[Driver][driver_email]": {
        required: "Entrez un email SVP",
      },
      "data[Driver][driver_phone]": {
        required: "Entrez votre numéro de téléphone SVP",
      },
      "data[Driver][address]": {
        required: "Entrez une adresse SVP",
      },
      "data[Driver][license_no]": {
        required: "Veuillez entrer le n° de permis",
      },
    }
  });



  var VehicleAdminAddvehicleForm = jQuery("#VehicleAdminAddvehicleForm").validate({
    rules: {
      "data[Vehicle][vehicle_name]": {
        required: true,
      },
      "data[Vehicle][model_name]": {
        required: true,
      },
      "data[Vehicle][color]": {
        required: true,
      },
      "data[Vehicle][year]": {
        required: true,
        number : true
      },
      "data[Vehicle][vehicle_no]": {
        required: true,
      },
    },
    messages: { 
      "data[Vehicle][vehicle_name]": {
        required: "Entrez le nom du véhicule svp",
      },
      "data[Vehicle][model_name]": {
        required: "Entrez le svp modèle de véhicule",
      },
      "data[Vehicle][color]": {
        required: "Entrez le svp couleur de véhicule",
      },
      "data[Vehicle][year]": {
        required: "Entrez le svp année",
      },
      "data[Vehicle][vehicle_no]": {
        required: "Entrez le svp véhicule immatriculation",
      },
    }
  });



  var VehicleAdminEditvehicleForm = jQuery("#VehicleAdminEditvehicleForm").validate({
    rules: {
      "data[Vehicle][vehicle_name]": {
        required: true,
      },
      "data[Vehicle][model_name]": {
        required: true,
      },
      "data[Vehicle][color]": {
        required: true,
      },
      "data[Vehicle][year]": {
        required: true,
        number : true
      },
      "data[Vehicle][vehicle_no]": {
        required: true,
      },
    },
    messages: { 
      "data[Vehicle][vehicle_name]": {
        required: "Entrez le nom du véhicule svp",
      },
      "data[Vehicle][model_name]": {
        required: "Entrez le svp modèle de véhicule",
      },
      "data[Vehicle][color]": {
        required: "Entrez le svp couleur de véhicule",
      },
      "data[Vehicle][year]": {
        required: "Entrez le svp année",
      },
      "data[Vehicle][vehicle_no]": {
        required: "Entrez le svp véhicule immatriculation",
      },
    }
  });

  var StateAddvalidator = jQuery("#StateAdminAddForm").validate({
    rules: {
      "data[State][state_name]": {
        required: true,
      },
      "data[State][country_id]":{
        required: true,
      }
    },
    messages: { 
      "data[State][state_name]": {
        required: "Sélectionner un département",
      },
      "data[State][country_id]": {
        required: "Sélectionnez le pays svp",
      }

    }
  });


  var StateEditvalidator = jQuery("#StateAdminEditForm").validate({
    rules: {
      "data[State][state_name]": {
        required: true,
      },
      "data[State][country_id]":{
        required: true,
      }
    },
    messages: { 
      "data[State][state_name]": {
        required: "Sélectionner un département",
      },
      "data[State][country_id]": {
        required: "Sélectionnez le pays svp",
      }

    }
  });

  var CountryAddvalidator = jQuery("#CountryAdminAddForm").validate({
    rules: {
      "data[Country][country_name]": {
        required: true,
      },
      "data[Country][iso]":{
        required: true,
      },
      "data[Country][phone_code]":{
        required: true,
        number:true,
      },
      "data[Country][currency_name]":{
        required: true,
      },
      "data[Country][currency_code]":{
        required: true,
      },
      "data[Country][currency_symbol]":{
        required: true,
      }

    },
    messages: { 
      "data[Country][country_name]": {
        required: "Entrez le nom de la pays svp",
      },
      "data[Country][iso]": {
        required: "Entrez le ISO svp",
      },
      "data[Country][phone_code]":{
        required: "Veuillez entrer l’indicatif téléphonique",
      },
      "data[Country][currency_name]":{
        required: "Entrer le nom de la devise svp",
      },
      "data[Country][currency_code]":{
        required: "Enrer le code de la devise svp",
      },
      "data[Country][currency_symbol]":{
        required: "Entrer le symbole de la devise svp",
      }

    }
  });

  var CountryEditvalidator = jQuery("#CountryAdminEditForm").validate({
    rules: {
      "data[Country][country_name]": {
        required: true,
      },
      "data[Country][iso]":{
        required: true,
      },
      "data[Country][phone_code]":{
        required: true,
        number:true,
      },
      "data[Country][currency_name]":{
        required: true,
      },
      "data[Country][currency_code]":{
        required: true,
      },
      "data[Country][currency_symbol]":{
        required: true,
      }

    },
    messages: { 
      "data[Country][country_name]": {
        required: "Entrez le nom de la pays svp",
      },
      "data[Country][iso]": {
        required: "Entrez le ISO svp",
      },
      "data[Country][phone_code]":{
        required: "Veuillez entrer l’indicatif téléphonique",
      },
      "data[Country][currency_name]":{
        required: "Entrer le nom de la devise svp",
      },
      "data[Country][currency_code]":{
        required: "Enrer le code de la devise svp",
      },
      "data[Country][currency_symbol]":{
        required: "Entrer le symbole de la devise svp",
      }

    }
  });
  var CityAddvalidator = jQuery("#CityAdminAddForm").validate({
    rules: {
      "data[City][country_id]": {
        required: true,
      },
      "data[City][state_id]": {
        required: true,
      },
      "data[City][city_name]":{
        required: true,
      }
    },
    messages: { 
      "data[City][country_id]": {
        required: "Sélectionnez le pays svp",
      },
      "data[City][state_id]": {
        required: "Sélectionner un département",
      },
      "data[City][city_name]": {
        required: "Entrez le nom de la ville svp",
      }

    }
  });
  var CityEditvalidator = jQuery("#CityAdminEditForm").validate({
    rules: {
      "data[City][country_id]": {
        required: true,
      },
      "data[City][state_id]": {
        required: true,
      },
      "data[City][city_name]":{
        required: true,
      }
    },
    messages: { 
      "data[City][country_id]": {
        required: "Sélectionnez le pays svp",
      },
      "data[City][state_id]": {
        required: "Sélectionner un département",
      },
      "data[City][city_name]": {
        required: "Sélectionner la ville SVP name",
      }

    }
  });

  var LocationAddvalidator = jQuery("#LocationAdminAddForm").validate({
    rules: {
        "data[Location][state_id]": {
          required: true,
          },
        "data[Location][city_id]":{
          required: true,
        },
        "data[Location][area_name]":{
          required: true,
        },
        "data[Location][zip_code]":{
          required: true,
          number:true,
        } 
      },
    messages: { 
      "data[Location][state_id]": {
        required: "Sélectionner un département",
      },
      "data[Location][city_id]": {
        required: "Sélectionner la ville SVP",
      },
      "data[Location][area_name]":{
          required: "Entrez votre région svp",
      },
      "data[Location][zip_code]":{
          required: "Entrez votre code postal svp",
      }
    }
  });

  var LocationEditvalidator = jQuery("#LocationAdminEditForm").validate({
    rules: {
        "data[Location][state_id]": {
          required: true,
          },
        "data[Location][city_id]":{
          required: true,
        },
        "data[Location][area_name]":{
          required: true,
        },
        "data[Location][zip_code]":{
          required: true,
          number:true,
        } 
      },
    messages: { 
      "data[Location][state_id]": {
        required: "Sélectionner un département",
      },
      "data[Location][city_id]": {
        required: "Sélectionner la ville SVP",
      },
      "data[Location][area_name]":{
          required: "Entrez votre région svp",
      },
      "data[Location][zip_code]":{
          required: "Entrez votre code postal svp",
      }
    }
  });
});