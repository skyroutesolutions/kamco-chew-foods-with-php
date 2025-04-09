<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <title>SplashDash</title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="MobileOptimized" content="320">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="assets/css/fonts.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/icofont.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="shortcut icon" type="image/png" href="assets/images/favicon.png">
</head>

<body>
    <div class="loader">
        <div class="spinner">
            <img src="assets/images/loader.gif" alt="Loading...">
        </div>
    </div>
    
    <!-- Main Body -->
    <div class="page-wrapper">
        
        <!-- Header -->
        <div id="header"></div>
        
        <!-- Sidebar -->
        <div id="sidebar"></div>
        
        <!-- Main Content -->
        <div class="page-wrapper">
            <div class="main-content">
                <!-- Page Title Start -->
                <div class="row">
                    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-title-wrapper">
                            <div class="page-title-box">
                                <h4 class="page-title bold">Dashboard</h4>
                            </div>
                            <div class="breadcrumb-list">
                                <ul>
                                    <li class="breadcrumb-link">
                                        <a href="index"><i class="fas fa-home mr-2"></i>Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-link active">Admin</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Start -->
             
                <!-- Revanue Status Start -->
              
                <!-- Products Orders Start -->
            
                <div class="ad-footer-btm">
                    <p>Copyright 2022 © SplashDash All Rights Reserved.</p>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div id="footer"></div>
    </div>
    
    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Load header, sidebar, and footer dynamically
            $('#header').load('layouts/header.html');
            $('#sidebar').load('layouts/sidebar.html');
            $('#footer').load('layouts/footer.html');
            
            // Hide loader after content loads
            $('.loader').fadeOut();
        });
    </script>
</body>
</html>
