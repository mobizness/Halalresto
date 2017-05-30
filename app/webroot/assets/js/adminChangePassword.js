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

  var UserAdminChangepassword = jQuery("#UserAdminChangepasswordForm").validate({
    rules: {     
        "data[User][oldpassword]": {
        required: true,
      },
      "data[User][newpassword]": {
        required: true,
      },
      "data[User][retypepassword]": {
        required: true,
        equalTo: '#UserPassword'
      }
    },
    messages: { 
      "data[User][oldpassword]": {
        required: "Please enter the old password",
      },
      "data[User][newpassword]": {
        required: "Please enter the new password",
      },
      "data[User][retypepassword]": {
        required: "Confirmer le mot de passe SVP",
        //equalTo : "Please enter the same "
      },
    }
  });
});