<!DOCTYPE html>
<html class="loading" lang="es">
<!-- BEGIN: Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Conectividad</title>
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="css/plantilla.css" rel="stylesheet">

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
    <div id="contenido">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    </div>
                    <ul class="nav navbar-nav float-right">
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon feather icon-maximize"></i></a></li>

                        <!-- Aqui van las cosas por cambiar en el logiout y todo eso -->
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <div class="user-nav d-sm-flex d-none"><span class="user-name text-bold-600">Juan Carrasco</span><span class="user-status">Activo</span></div><span><img class="round" src="images/perfil.jpg" alt="avatar" height="40" width="40"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto">
                    <a class="navbar-brand" href="../../../html/ltr/vertical-menu-template/index.html">
                        <div class="brand-logo"></div>
                        <h2 class="brand-text mb-0">Scairp</h2>
                    </a>
                </li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="icon-disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

                <li class=" navigation-header"><span>Módulos</span>
                </li>
                </li>
                <li class=" nav-item active" @click="menu=0"><a href="#"><i class="feather icon-home"></i><span class="menu-title" data-i18n="Chat">Dashboard</span></a>
                </li>
                <li class=" nav-item" @click="menu=1"><a href="#"><i class="feather icon-home"></i><span class="menu-title" data-i18n="Chat">Empresa</span></a>
                </li>
                <li class=" nav-item" @click="menu=2"><a href="#"><i class="feather icon-mail"></i><span class="menu-title" data-i18n="Email">Mensajes</span></a>
                </li>
                <li class=" nav-item"><a href="app-todo.html"><i class="feather icon-users"></i><span class="menu-title" data-i18n="Todo">Usuarios</span></a>
                </li>
                <li class=" nav-item"><a href="app-calender.html"><i class="feather icon-dollar-sign"></i><span class="menu-title" data-i18n="Calender">Cuentas</span></a>
                </li>
                <li class=" nav-item"><a href="#"><i class="feather icon-shopping-cart"></i><span class="menu-title" data-i18n="Ecommerce">MarketPlace</span></a>
                    <ul class="menu-content">
                        <li><a href="app-ecommerce-shop.html"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="Shop">Shop</span></a>
                        </li>
                        <li><a href="app-ecommerce-details.html"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="Details">Details</span></a>
                        </li>
                        <li><a href="app-ecommerce-wishlist.html"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="Wish List">Wish List</span></a>
                        </li>
                        <li><a href="app-ecommerce-checkout.html"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="Checkout">Checkout</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="feather icon-settings"></i><span class="menu-title" data-i18n="User">Ajustes</span></a>
                    <ul class="menu-content">
                        <li><a href="app-user-list.html"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="List">List</span></a>
                        </li>
                        <li><a href="app-user-view.html"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="View">View</span></a>
                        </li>
                        <li><a href="app-user-edit.html"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="Edit">Edit</span></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <template v-if="menu==1">
                    <example-component></example-component>
                </template>
                <template v-if="menu==2">
                    <h1>Segundo Módulo</h1>
                </template>
                <template v-if="menu==0">
                    <h1>Dashboard</h1>
                </template>
            </div>
        </div>
    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    <footer class="footer footer-static footer-light">
        <p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">Conectividad &copy; 2020<a class="text-bold-800 grey darken -2" href="https://www.facebook.com/ScairpGroup" target="_blank">Scairp</a>, All rights Reserved</span><span class="float-md-right d-none d-md-block">Carrasco Purisaca Juan<i class="feather icon-heart pink"></i></span>
        </p>
    </footer>
    </div>
    <script src="js/plantilla.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
