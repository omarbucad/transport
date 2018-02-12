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

    <!-- Bootstrap Material Datetime Picker Css -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') ?>">

    <!-- Sweetalert Css -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/plugins/sweetalert/sweetalert.css') ?>">

    <!-- Wait Me Css -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/plugins/waitme/waitMe.css') ?>">

    <!-- Bootstrap Select Css -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/plugins/bootstrap-select/css/bootstrap-select.css') ?>">

     <!-- JQuery DataTable Css -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') ?>">

    <!-- Light Gallery Plugin Css -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/plugins/light-gallery/css/lightgallery.css') ?>">

    <!-- Bootstrap Material Datetime Picker Css -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') ?>">

    <!-- Custom Css -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/css/style.css?version=5') ?>">
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/css/icons.css') ?>">

    <!-- <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/css/slider.min.css') ?>"> -->

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link rel="stylesheet" type="text/css" href=" <?php echo site_url('public/css/themes/all-themes.css') ?>">

    <!-- Jquery Core Js -->
    <script src="<?php echo site_url('public/plugins/jquery/jquery.min.js') ?>"></script>

    <!-- Moment Plugin Js -->
    <script src="<?php echo site_url('public/plugins/momentjs/moment.js') ?>"></script>

    <script src="<?php echo site_url('public/js/pdfobject.js') ?>"></script>

    <style type="text/css">
        .lg-backdrop{
            z-index: 1051 !important;
        }
        .lg-outer{
            z-index: 1052 !important;
        }
        .click-notification.unread{
            background-color: #e9e9e9 !important;
        }
    </style>
</head>
<body class="theme-red">
    <!-- Page Loader -->
    <!-- <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div> -->
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <!-- <div class="overlay"></div> -->
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <!-- <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div> -->
    <!-- #END# Search Bar -->

    <!-- Top Bar -->
    <?php $this->load->view('common/top') ?>

    <!-- #Top Bar -->
    <?php $this->load->view('common/side') ?>

    <section class="content">
        <div class="container-fluid">
            <?php $this->load->view($page) ?>
        </div>
    </section>

    <?php $this->load->view('common/modal'); ?>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo site_url('public/plugins/bootstrap/js/bootstrap.js') ?>"></script>

    <!-- Select Plugin Js -->
    <script src="<?php echo site_url('public/plugins/bootstrap-select/js/bootstrap-select.js') ?>"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo site_url('public/plugins/jquery-slimscroll/jquery.slimscroll.js') ?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo site_url('public/plugins/node-waves/waves.js') ?>"></script>
    
    <!-- SweetAlert Plugin Js -->
    <script src="<?php echo site_url('public/plugins/sweetalert/sweetalert.min.js') ?>"></script>
 

    <!-- Autosize Plugin Js -->
    <script src="<?php echo site_url('public/plugins/autosize/autosize.js') ?>"></script>

    <!-- Input Mask Plugin Js -->
    <script src="<?php echo site_url('public/plugins/jquery-inputmask/jquery.inputmask.bundle.js') ?>"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="<?php echo site_url('public/plugins/jquery-countto/jquery.countTo.js') ?>"></script>
    
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="<?php echo site_url('public/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') ?>"></script>
    
    <!-- Jquery Validation Plugin Css -->
    <script src="<?php echo site_url('public/plugins/jquery-validation/jquery.validate.js') ?>"></script>
    
    <!-- JQuery Steps Plugin Js -->
    <script src="<?php echo site_url('public/plugins/jquery-steps/jquery.steps.js') ?>"></script>

    <!-- noUISlider Plugin Js -->
    <script src="<?php echo site_url('public/plugins/nouislider/nouislider.js') ?>"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="<?php echo site_url('public/plugins/jquery-sparkline/jquery.sparkline.js') ?>"></script>
    
    <!-- Jquery DataTable Plugin Js -->
    <script src="<?php echo site_url('public/plugins/jquery-datatable/jquery.dataTables.js') ?>"></script>
    <script src="<?php echo site_url('public/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') ?>"></script>


    <!-- Light Gallery Plugin Js -->
    <script src="<?php echo site_url('public/plugins/light-gallery/js/lightgallery-all.js') ?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo site_url('public/js/admin.js?version=2') ?>"></script>
    <script src="<?php echo site_url('public/js/pages/medias/image-gallery.js?version=1') ?>"></script>
    <script src="<?php echo site_url('public/js/pages/index.js?version=1') ?>"></script>
    <script src="<?php echo site_url('public/js/pages/tables/jquery-datatable.js') ?>"></script>
    <script src="<?php echo site_url('public/js/pages/ui/tooltips-popovers.js') ?>"></script>
    <script src="<?php echo site_url('public/js/jquery.lazyload.js') ?>"></script>
    <script src="<?php echo site_url('public/js/pages/ui/modals.js') ?>"></script>
    <script src="<?php echo site_url('public/js/pages/widgets/infobox/infobox-4.js') ?>"></script>
    <script src="<?php echo site_url('public/js/pages/forms/basic-form-elements.js?version=4') ?>"></script>
    <script src="<?php echo site_url('public/js/pages/forms/form-validation.js') ?>"></script>
</body>
</html>