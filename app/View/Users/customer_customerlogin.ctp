<script>

    function fb_login() {
        FB.login(function (response) {
            if (response.authResponse) {
                statusChangeCallback(response);
            } else {
                //user hit cancel button
                console.log('User cancelled login or did not fully authorize.');

            }
        }, {scope: 'email,public_profile'});
    }

    function statusChangeCallback(response) {
        console.log(response);
        // The response object is returned with a status field that lets the
        // app know the current login status of the person.
        // Full docs on the response object can be found in the documentation
        // for FB.getLoginStatus().
        if (response.status === 'connected') {
            // Logged into your app and Facebook.
            testAPI();
        } else if (response.status === 'not_authorized') {
            // The person is logged into Facebook, but not your app.
            console.log('Please log ' + 'into this app.');
        } else {
            // The person is not logged into Facebook, so we're not sure if
            // they are logged into this app or not.
            console.log("Please Log in ");
        }
    }

    // This function is called when someone finishes with the Login
    // Button.  See the onlogin handler attached to it in the sample
    // code below.
    function checkLoginState() {

        $(".signin-loader").show();
        $(".form-group").css("opacity", "0.4");
        $("#submit").prop('disabled', true);

        FB.getLoginStatus(function (response) {
            statusChangeCallback(response);
        });
    }

    window.fbAsyncInit = function () {
        FB.init({
            appId: '1751863181732370',
            cookie: true, // enable cookies to allow the server to access 
            // the session
            xfbml: true, // parse social plugins on this page
            version: 'v2.4' // use version 2.2
        });
        // Now that we've initialized the JavaScript SDK, we call 
        // FB.getLoginStatus().  This function gets the state of the
        // person visiting this page and can return one of three states to
        // the callback you provide.  They can be:
        //
        // 1. Logged into your app ('connected')
        // 2. Logged into Facebook, but not your app ('not_authorized')
        // 3. Not logged into Facebook and can't tell if they are logged into
        //    your app or not.
        //
        // These three cases are handled in the callback function.

        FB.getLoginStatus(function (response) {
            //  statusChangeCallback(response);
        });
    };
    // Load the SDK asynchronously
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    // Here we run a very simple test of the Graph API after login is
    // successful.  See statusChangeCallback() for when this call is made.
    function testAPI() {

        FB.api('/me?fields=id,email,first_name,last_name', function (response) {
            var email = response.email;
            if (email.length !== 0 && email != null) {

                var Fname = response.first_name;
                var Lname = response.last_name;
                var fId = response.id;
                var roleId = "4";
                //role_id, username, first_name last_name email

                $.ajax({
                    type: "POST",
                    url: "<?php echo $siteUrl.'/mobileApi/request'; ?>",
                    data: {action: "facebookLogin", email: email, username: Fname + " " + Lname, role_id: roleId, first_name: Fname, last_name: Lname},
                    success: function (result) {
                        var res = JSON.parse(result);
                        result = res.message;
                        var clean_url = result.replace(/\/$/, '');
                        clean_url = "<?php echo $siteUrl; ?>" + clean_url;
                        window.location.replace(clean_url);
                    }
                });
            } else {
                $("#alerts").show();
                $("#alerts").removeClass("alert-success");
                $("#alerts").addClass("alert-danger");
                $("#alerts").text("Oops !! You haven't activated your facebook's email address");
            }

        });
    }

</script>


<div class="loginBg">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-lg-6 col-lg-offset-3 col-md-offset-2">
                <div id="login" class="loginInnerBg clearfix">
                    <h4> <?php echo __('Login', true); ?></h4>
                    <div class="social-logins clearfix">
                        <div class="col-sm-6">
                            <a  scope="public_profile,email" class="facebook fb-login-button" data-max-rows="1" data-size="xlarge" data-show-faces="false" data-auto-logout-link="false" href="#" onclick="fb_login()">                    
                                <img alt="facebook" title="facebook" src="<?php echo $siteUrl.'/frontend/images/facebook.png'; ?>">
                                <i><?php echo __('Login with Facebook');?></i>
                                <div class="clr"></div>
                            </a>
                        </div>
                        <!--<a class="twitter" href="javascript:voic(0);">
                                <img alt="twitter" title="twitter" src="<?php echo $siteUrl.'/frontend/images/twitter.png'; ?>">
                                <i>Login with Twitter</i>
                                <div class="clr"></div>
                        </a>-->
                        <div class="col-sm-6">
                            <a class="googleplus" href="<?php echo $siteUrl.'/users/social_login/Google'; ?>">
                                <img alt="gplus" title="gplus" src="<?php echo $siteUrl.'/frontend/images/gplus.png'; ?>">
                                <i><?php echo __('Login with Google+');?></i>
                                <div class="clr"></div>
                            </a>
                        </div>
                    </div>	
                    <div class="socialOr"><span>[ ou ]</span></div>
					<?php echo $this->Form->create('User', array('class' => 'login-form')); ?>
                    <div class="form-group">
                        <label class="control-label"> <?php echo __('Email', true); ?></label> <?php
							echo $this->Form->input('username',
												array('label' => false,
													  'placeholder' => __('Email'),
													  'class'=>'form-control placeholder-no-fix',
													  'autocomplete' => 'off',
													  'div' => false)); ?>
                    </div>
                    <div class="form-group">
                        <label class="control-label"> <?php echo __('Password', true); ?></label><?php
							echo $this->Form->input('password',
												array('label' => false,
													  'placeholder' => __('Password'),
													  'class'=>'form-control placeholder-no-fix',
													  'autocomplete' => 'off',
													  'div' => false)); ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="rememberpassword padding-l-15-xs">
                                <div class='checkbox checkbox-inline'>
											<?php
					        				echo $this->Form->input("rememberMe",
					        							array("type"=>"checkbox",
					        									'label'=>false,
					        									'id' => 'remember',
					        									'div' =>false));
					        				?> 
                                    <label for="remember"><?php echo __('Remember me', true);?></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6" id="forget">
                            <div class="rememberpassword text-right text-left-xs">
                                <a id="forgetPage" class="linkRight" href="javascript:void(0);"><?php echo __('Forget Password'); ?> ?</a>
                            </div>
                        </div>
                    </div>


                    <div class="signup-footer margin-t-15-xs">
							<?php echo $this->Form->submit(__('Login'));?>
                        <div class="newuser"><?php echo __('New user');?> ? <a href="<?php echo $siteUrl.'/signup'; ?>"> <?php echo __('Create New Account', true); ?> ?</a>
                        </div>
                    </div> <?php
					echo $this->Form->hidden('token', array('value' => $token));
					echo $this->Form->end(); ?>

                </div>

                <div class="loginInnerBg clearfix" id="forgetsmail" style="display:none">
                    <h4> <?php echo __('Forget Mail'); ?> </h4><?php
					echo $this->Form->create('Users', array('class' => 'login-form','id'=>'forgetmail')); ?>
                    <div class="form-group">
                        <label class="control-label"> <?php echo __('Email', true); ?></label> <?php
							echo $this->Form->input('email',
												array('label' => false,
													'placeholder' => __('Email'),
													'class'=>'form-control placeholder-no-fix',
													'autocomplete' => 'off',
													'div' => false)); ?>
                    </div>
                    <div class="signup-footer">
							<?php echo $this->Form->submit(__('Submit'));?>
                        <div class="newuser">
                            Retour au <a id="loginPage" href="javascript:void(0);"><?php echo __('Login', true); ?></a>
                        </div>
                    </div> <?php

					echo $this->Form->end(); ?>
                </div>
                <!-- <div class="socialOr"><span>Or</span></div> -->
            </div>
        </div>
    </div>
</div>