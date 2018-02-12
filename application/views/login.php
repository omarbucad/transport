<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title><?php echo $title; ?></title>

   

	<!-- Favicon-->
    <link rel="icon" href="<?php echo site_url('public/images/logoicon.png') ?>" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/plugins/bootstrap/css/bootstrap.css') ?>">

    <!-- Waves Effect Css -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/plugins/node-waves/waves.css') ?>">

    <!-- Animation Css -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/plugins/animate-css/animate.css') ?>">

    <!-- Custom Css -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/css/style.css') ?>">

    <!-- Jquery Core Js -->
    <script src="<?php echo site_url('public/plugins/jquery/jquery.min.js') ?>"></script>

    <style type="text/css">
        .login-page{
            background-image: url( "<?php echo site_url('public/images/image-gallery/4.jpg') ?>");
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="login-page">
     <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Transport<b>APP</b></a>
            <small>Trackerteer Web Developer Corporation</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST" action="<?php echo site_url('login'); ?>">
                    <?php 
                        if($this->input->get('status') && $this->input->get('status') == 'success'){
                             ?>
                            <div class="alert alert-success">
                                <strong>Well done!</strong> You have successfully Created an Account!
                            </div>  
                            <?php
                        }else if($this->input->get('status') && $this->input->get('status') == 'forgottenpasswordsend'){
                             ?>
                            <div class="alert alert-success">
                                <strong>Well done!</strong> Forgotten Password Link has been sent to your email.
                            </div>  
                            <?php
                        }else if($this->input->get('status') && $this->input->get('status') == 'forgottenpasswordsuccess'){
                             ?>
                            <div class="alert alert-success">
                                <strong>Well done!</strong> Password has been reset. Check your email for the new password
                            </div>  
                            <?php
                        }else{
                             switch ($loginError) {
                                case 'SUCCESS':
                                    ?>
                                    <div class="alert alert-success">
                                        <strong>Well done!</strong> You have successfully logged out!
                                    </div>  
                                    <?php
                                    break;
                                case 'ERROR':
                                    ?>
                                    <div class="alert alert-danger">
                                        <strong>Oh snap!</strong> Invalid Username / Password
                                    </div>  
                                    <?php
                                    break;
                                default:
                                    ?>
                                    <div class="msg">You can Login</div>
                                    <?php
                                    break;
                            }
                        }

                    ?>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" placeholder="Username" required autofocus autocomplete="off" >
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" required autocomplete="off" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN</button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                            <a href="<?php echo site_url('login/register'); ?>">Register Now!</a>
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="<?php echo site_url('login/forgotPassword'); ?>">Forgot Password?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>    


    <!-- Bootstrap Core Js -->
    <script src="<?php echo site_url('public/plugins/bootstrap/js/bootstrap.js') ?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo site_url('public/plugins/node-waves/waves.js') ?>"></script>

    <!-- Validation Plugin Js -->
    <script src="<?php echo site_url('public/plugins/jquery-validation/jquery.validate.js') ?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo site_url('public/js/admin.js') ?>"></script>
    <script src="<?php echo site_url('public/js/pages/examples/sign-in.js') ?>"></script>
</body>
</html>