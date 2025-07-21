<!--

=========================================================
* Now UI Kit - v1.3.1
=========================================================

* Product Page: https://www.creative-tim.com/product/now-ui-kit-pro
* Copyright 2019 Creative Tim (http://www.creative-tim.com)
* Licensed under MIT (https://github.com/creativetimofficial/now-ui-kit/blob/master/LICENSE.md)

* Designed by www.invisionapp.com Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

-->
<!DOCTYPE html>
<html lang="en">

<head>
	
	
    <meta charset="utf-8"/>

    <meta property="og:title" content="Desenvolvimento Full Stack - Quatro Ventos">
    <meta property="og:site_name" content="Design, Code, Test, Repeat...">
    <meta property="og:url" content="https://www.quatroventos.com.br/desenvolvimento-full-stack">
    <meta property="og:description" content="Somos uma agência de desenvolvimento de sites ágil, com uma equipe preparada para desenvolver e manter qualquer tipo de projeto web.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://www.quatroventos.com.br/../assets/img/desenv-fullstack-hero.png">

    <meta color="#31355C" >
    <meta name="theme-color" content="#31355C" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#31355C">

    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Desenvolvimento Full Stack - Quatro Ventos
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200|Open+Sans+Condensed:700"
          rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">
    <!-- CSS Files -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="../assets/css/now-ui-kit.css?v=1.3.1" rel="stylesheet"/>
    <link href="../assets/owl.carousel.min.css" rel="stylesheet"/>
    <link href="../assets/owl.theme.default.min.css" rel="stylesheet"/>
    <link href="../assets/demo/demo.css" rel="stylesheet"/>
    <link href="../assets/css/fullpage.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
          integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
          crossorigin="anonymous"/>
</head>

<body class="index-page sidebar-collapse">

<?php include('header.php');
//https://www.canva.com/colors/color-palettes/thwarted-summer-shower/
//https://coolors.co/687175-a4d6eb-eac043-cb682f-090706
?>

<div class="wrapper" id="fullpage">

    <div class="page-header header-filter section">
        <div class="page-header-image" style="background-color: #31355C"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 ml-auto mr-auto text-left" data-aos="fade-right" data-aos-duration="600">
                    <h1 class="main-title"><small>Full Stack</small> Development</h1>
                    <h5 class="main-description"> We are <strong>ready to make</strong> your next web project.</h5>
                </div>
                <div class="col-md-6 text-right" data-aos="fade-left" data-aos-duration="600" style="margin-top:100px;">
                    <img src="../assets/img/desenv-fullstack-hero.png" class="img-hero">
                </div>
            </div>
        </div>
    </div>

    <!-- portfolio -->
    <div class="section portfolio" style="padding:0; background:rgb(235, 248, 254);">
        <br><br><br>
        <!--<div class="slide">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-6 offset-md-2 text-left" data-aos="fade-up" data-aos-duration="300">
                        <h1 class="main-title" style="margin-top:10px">Nossos trabalhos</h1>
                        <h5 class="main-description">Veja alguns dos nossos sites mais recentes.</h5>
                    </div>
                    <div class="col-md-2 d-none d-m-block d-lg-block" data-aos="fade-left" data-aos-duration="600" data-aos-delay="200">
                        <img src="../assets/img/direcional.png" alt="Use o direcional para navegar." data-toggle="tooltip" data-placement="bottom" title="Use as setas do teclado para navegar.">
                    </div>
                </div>
            </div>
        </div>-->
        <!-- portfolio -->
        <?php include("portfolio.php"); ?>
        <!-- portfolio -->
    </div>
    <!-- tecnologias -->
    <div class="section fp-auto-height" style="padding:0; background-color:#e26f6f;">
        <br><br><br>
        <div id="tecnologias" class="features-7" style="padding:30px 0;">
            <div class="col-md-8 mr-auto ml-auto text-center">
                <h2 class="title" style="color:white;">Technologies we love to work with</h2>
            </div><br><br>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="owl-carousel owl-theme" data-aos="fade-up" >

                            <div class="card"><div class="card-body">
                                    <div class="icon">
                                        <i class="fab fa-sketch" style="color:#EEA940;"></i>
                                    </div>
                                    <div class="description">
                                        <h5 class="info-title">Sketch</h5>
                                    </div>
                                </div></div>

                            <div class="card"><div class="card-body">
                                    <div class="icon">
                                        <i class="devicon-bootstrap-plain colored"></i>
                                    </div>
                                    <div class="description">
                                        <h5 class="info-title">Bootstrap</h5>
                                    </div>
                                </div></div>

                            <div class="card"><div class="card-body">
                                    <div class="icon">
                                        <i class="devicon-wordpress-plain colored"></i>
                                    </div>
                                    <div class="description">
                                        <h5 class="info-title">Wordpress</h5>
                                    </div>
                                </div></div>

                            <div class="card"><div class="card-body">
                                    <div class="icon">
                                        <i class="fab fa-elementor" style="color:#802D68;"></i>
                                    </div>
                                    <div class="description">
                                        <h5 class="info-title">Elementor</h5>
                                    </div>
                                </div></div>

                            <div class="card"><div class="card-body">
                                    <div class="icon">
                                        <i class="devicon-css3-plain colored"></i>
                                    </div>
                                    <div class="description">
                                        <h5 class="info-title">CSS3</h5>
                                    </div>
                                </div></div>

                            <div class="card"><div class="card-body">
                                    <div class="icon">
                                        <i class="devicon-php-plain colored"></i>
                                    </div>
                                    <div class="description">
                                        <h5 class="info-title">PHP</h5>
                                    </div>
                                </div></div>

                            <div class="card"><div class="card-body">
                                    <div class="icon">
                                        <i class="devicon-javascript-plain colored"></i>
                                    </div>
                                    <div class="description">
                                        <h5 class="info-title">Javascript</h5>
                                    </div>
                                </div></div>

                            <div class="card"><div class="card-body">
                                    <div class="icon">
                                        <i class="devicon-jquery-plain colored"></i>
                                    </div>
                                    <div class="description">
                                        <h5 class="info-title">Jquery</h5>
                                    </div>
                                </div></div>

                            <div class="card"><div class="card-body">
                                    <div class="icon">
                                        <i class="devicon-html5-plain colored"></i>
                                    </div>
                                    <div class="description">
                                        <h5 class="info-title">HTML5</h5>
                                    </div>
                                </div></div>

                            <div class="card"><div class="card-body">
                                    <div class="icon">
                                        <i class="devicon-photoshop-plain colored"></i>
                                    </div>
                                    <div class="description">
                                        <h5 class="info-title">Photoshop</h5>
                                    </div>
                                </div></div>

                            <div class="card"><div class="card-body">
                                    <div class="icon">
                                        <i class="devicon-illustrator-plain colored"></i>
                                    </div>
                                    <div class="description">
                                        <h5 class="info-title">Illustrator</h5>
                                    </div>
                                </div></div>

                        </div></div>

                </div>

            </div>
            <br><br><br>
        </div>
    </div>
    <!-- tecnologias -->


    <!-- Outros serviços -->
    <div class="section" style="padding:0; background:rgb(235, 248, 254);">
        <br><br><br>
        <div class="manutencao">
            <div class="col-md-8 mr-auto ml-auto text-center">
                <h2 class="title">What can we do for you?</h2>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <div class="card" data-aos="fade-up">
                            <div class="card-body">
                                <div class="description text-center">
                                    <img src="../assets/img/icones-servicos/manutencao.png">
                                    <h5 class="info-title">Maintenance</h5>
                                    <a href="manutencao-de-sites" class="btn btn-primary btn-round">More info</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="card" data-aos="fade-up" data-aos-delay="100">
                            <div class="card-body">
                                <div class="description text-center">
                                    <img src="../assets/img/icones-servicos/desenv-fullstack.png">
                                    <h5 class="info-title">Full Stack Development</h5>
                                    <a href="desenvolvimento-full-stack" class="btn btn-primary btn-round">More info</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="card" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-body">
                                <div class="description text-center">
                                    <img src="../assets/img/icones-servicos/hosting.png">
                                    <h5 class="info-title">Hosting</h5>
                                    <a href="hospedagem-de-sites" class="btn btn-primary btn-round">More info</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br><br>
        </div>
    </div>
    <!-- Outros serviços -->



    <div class="section" style="padding:0;">
        <?php include('sessao-contato.php'); ?>
    </div>

<?php include('footer.php'); ?>