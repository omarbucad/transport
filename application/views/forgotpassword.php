<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title><?php echo $title; ?></title>

	<!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

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
                <form id="forgot_password" method="POST">
                    <div class="msg">
                        Enter your email address that you used to register. We'll send you an email with your username and a
                        link to reset your password.
                    </div>
                    <?php 
                        if($message){
                            ?>
                                <div class="alert alert-danger">
                                    <strong>Oh snap!</strong> <?php echo $message; ?>
                                </div>  
                            <?php
                        }
                    ?>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email" placeholder="Email" required autofocus>
                        </div>
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">RESET MY PASSWORD</button>

                    <div class="row m-t-20 m-b--5 align-center">
                        <a href="<?php echo site_url('login'); ?>">Sign In!</a>
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