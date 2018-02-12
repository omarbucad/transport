<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title><?php echo $title; ?></title>

	<!-- Favicon-->
    <link rel="icon" href="<?php echo site_url('public/images/logo.png') ?>" type="image/x-icon">

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
                <form id="sign_in" method="POST" action="<?php echo site_url('login/register'); ?>">
                    <div class="msg">Register a new company</div>
                     <?php echo validation_errors(); ?>
                     <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">home</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="company" value="<?php echo set_value('company'); ?>" placeholder="Company" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="name" value="<?php echo set_value('name'); ?>" placeholder="Name Surname" required >
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">account_box</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" value="<?php echo set_value('username'); ?>" placeholder="Username" required >
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email" value="<?php echo set_value('email'); ?>" placeholder="Email Address" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" value="<?php echo set_value('password'); ?>" minlength="6" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="confirm" minlength="6" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="terms" id="terms" class="filled-in chk-col-pink" required="">
                        <label for="terms">I read and agree to the <a href="javascript:void(0);" data-toggle="modal" data-target="#defaultModal">terms of usage</a>.</label>
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">SIGN UP</button>

                    <div class="m-t-25 m-b--5 align-center">
                        <a href="<?php echo site_url('login'); ?>">You already have a membership?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>    

    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h1>Terms of Use ("Terms")</h1>


                    <p>Last updated: May 12, 2017</p>


                    <p>Please read these Terms of Use ("Terms", "Terms of Use") carefully before using the http://www.trackerteer.com/transport/ website (the "Service") operated by Transport App ("us", "we", or "our").</p>

                    <p>Your access to and use of the Service is conditioned on your acceptance of and compliance with these Terms. These Terms apply to all visitors, users and others who access or use the Service.</p>

                    <p>By accessing or using the Service you agree to be bound by these Terms. If you disagree with any part of the terms then you may not access the Service. This Terms of Use is licensed by <a href="https://termsfeed.com" rel="nofollow">TermsFeed Generator</a> to Transport App.</p>


                    <h2>Accounts</h2>

                    <p>When you create an account with us, you must provide us information that is accurate, complete, and current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate termination of your account on our Service.</p>

                    <p>You are responsible for safeguarding the password that you use to access the Service and for any activities or actions under your password, whether your password is with our Service or a third-party service.</p>

                    <p>You agree not to disclose your password to any third party. You must notify us immediately upon becoming aware of any breach of security or unauthorized use of your account.</p>


                    <h2>Links To Other Web Sites</h2>

                    <p>Our Service may contain links to third-party web sites or services that are not owned or controlled by Transport App.</p>

                    <p>Transport App has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party web sites or services. You further acknowledge and agree that Transport App shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by or in connection with use of or reliance on any such content, goods or services available on or through any such web sites or services.</p>

                    <p>We strongly advise you to read the terms and conditions and privacy policies of any third-party web sites or services that you visit.</p>


                    <h2>Termination</h2>

                    <p>We may terminate or suspend access to our Service immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.</p>

                    <p>All provisions of the Terms which by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity and limitations of liability.</p>

                    <p>We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.</p>

                    <p>Upon termination, your right to use the Service will immediately cease. If you wish to terminate your account, you may simply discontinue using the Service.</p>

                    <p>All provisions of the Terms which by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity and limitations of liability.</p>


                    <h2>Governing Law</h2>

                    <p>These Terms shall be governed and construed in accordance with the laws of Philippines, without regard to its conflict of law provisions.</p>

                    <p>Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights. If any provision of these Terms is held to be invalid or unenforceable by a court, the remaining provisions of these Terms will remain in effect. These Terms constitute the entire agreement between us regarding our Service, and supersede and replace any prior agreements we might have between us regarding the Service.</p>


                    <h2>Changes</h2>

                    <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material we will try to provide at least 30 days notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.</p>

                    <p>By continuing to access or use our Service after those revisions become effective, you agree to be bound by the revised terms. If you do not agree to the new terms, please stop using the Service.</p>


                    <h2>Contact Us</h2>

                    <p>If you have any questions about these Terms, please contact us.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
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