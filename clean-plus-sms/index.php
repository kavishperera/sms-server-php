<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="resource/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- daterange picker -->
        <link rel="stylesheet" href="resource/plugins/daterangepicker/daterangepicker.css">
        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="resource/plugins/datepicker/datepicker3.css">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="resource/plugins/iCheck/all.css">
        <!-- Bootstrap Color Picker -->
        <link rel="stylesheet" href="resource/plugins/colorpicker/bootstrap-colorpicker.min.css">
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="resource/plugins/timepicker/bootstrap-timepicker.min.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="resource/plugins/select2/select2.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="resource/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="resource/dist/css/skins/_all-skins.css">
        <!-- SmartScroll -->
        <link href="resource/dist/css/SmartScroll.css" rel="stylesheet" type="text/css"/>
        <!-- Angular -->
        <link href="resource/angular/angular-ui-notification.min.css" rel="stylesheet" type="text/css"/>
    </head>
    <body ng-app="appModule" class="hold-transition" ng-class="{'skin-red sidebar-mini': loginStatus , 'login-page': !loginStatus}">
        <div class="wrapper">
            <header class="main-header" ng-show="loginStatus">
                <a class="logo">
                    <span class="logo-mini">SMS</span>
                    <img src="resource/img/logo.png" class="logo-lg" style="margin-left: 20px;margin-top: 10px;"/>
                </a>
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li class="dropdown user user-menu">
                                <a href="#/" class="dropdown-toggle">
                                    <span class="hidden-xs">LOGOUT</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>

            <aside class="main-sidebar" ng-show="loginStatus">
                <section class="sidebar">
                    <ul class="sidebar-menu">
                        <br><br>
                        <li><a href="#/client-sms-reload"><i class="fa fa-archive"></i> <span> SMS Relord</span></a></li>
                        <li class="treeview">
                            <a href="">
                                <i class="fa fa-balance-scale"></i>
                                <span>SMS HISTORY</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#/client-sms-reload-history"><i class="fa fa-circle-o"></i> Reload History</a></li>
                                <li><a href="#/client-sms-mesage-history"><i class="fa fa-circle-o"></i> SMS History</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="">
                                <i class="fa fa-users"></i>
                                <span>MASTER FILES</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#/manage-client"><i class="fa fa-circle-o"></i> Manage Client</a></li>
                            </ul>
                        </li>
<!--                        <li class="treeview">
                            <a href="">
                                <i class="fa-bar-chart-o"></i>
                                <span>REPOSRTS</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#/reports"><i class="fa fa-circle-o"></i> Client Details</a></li>
                            </ul>
                        </li>-->
                        <li><a href="#/read-file"><i class="fa fa-edit"></i> <span>Read Me</span></a></li>
                    </ul>
                </section>  
            </aside>

            <div ng-class="{'content-wrapper': loginStatus}" ng-view style="height: 620px;">
            </div>

            <footer class="main-footer" ng-show="loginStatus">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.0
                </div>
                <strong>Copyright &copy; 2017-2018 <a target="_blank" href="http://supervisionglobal.com">SUPER VISION TEC</a>.</strong> All rights
                reserved.
            </footer>
            <div class="control-sidebar-bg"></div>
        </div>
        <!-- jQuery 2.2.3 -->
        <script src="resource/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="resource/bootstrap/js/bootstrap.min.js"></script>
        <!-- Select2 -->
        <script src="resource/plugins/select2/select2.full.min.js"></script>
        <!-- InputMask -->
        <script src="resource/plugins/input-mask/jquery.inputmask.js"></script>
        <script src="resource/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
        <script src="resource/plugins/input-mask/jquery.inputmask.extensions.js"></script>
        <!-- date-range-picker -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
        <script src="resource/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- bootstrap datepicker -->
        <script src="resource/plugins/datepicker/bootstrap-datepicker.js"></script>
        <!-- bootstrap color picker -->
        <script src="resource/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
        <!-- bootstrap time picker -->
        <script src="resource/plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <!-- SlimScroll 1.3.0 -->
        <script src="resource/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- iCheck 1.0.1 -->
        <script src="resource/plugins/iCheck/icheck.min.js"></script>
        <!-- FastClick -->
        <script src="resource/plugins/fastclick/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="resource/dist/js/app.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="resource/dist/js/demo.js"></script>

        <!-- Angular -->
        <script src="resource/angular/angular.min.js" type="text/javascript"></script>
        <script src="resource/angular/angular-route.min.js" type="text/javascript"></script>
        <script src="angular-sanitize/angular-sanitize.min.js" type="text/javascript"></script>
        <script src="resource/angular/angular-bootstrap/ui-bootstrap.min.js" type="text/javascript"></script>
        <script src="resource/angular/angular-bootstrap/ui-bootstrap-tpls.min.js" type="text/javascript"></script>
        <script src="resource/angular/angular-ui-notification.min.js" type="text/javascript"></script>
        <script src="resource/angular/angular-cookies.min.js" type="text/javascript"></script>

        <script src="app/index.js" type="text/javascript"></script>
        <script src="app/master/client/client.js" type="text/javascript"></script>
        <script src="app/transaction/client-reloard/client-reloard.js" type="text/javascript"></script>
        <script src="app/transaction/client-reloard-history/client-reloard-history.js" type="text/javascript"></script>
        <script src="app/transaction/client-sms-mesage-history/client-sms-mesage-history.js" type="text/javascript"></script>
        <script src="app/directives/dirPagination.js" type="text/javascript"></script>
        <script src="app/system/login/login-controller.js" type="text/javascript"></script>
        <script src="app/system/login/login-service.js" type="text/javascript"></script>
    </body>
</html>
