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

<?php
//
//require_once('geoplugin.class.php');
//$geoplugin = new geoPlugin();
//$geoplugin->locate();
//// create a variable for the country code
//$var_country_code = $geoplugin->countryCode;
//// redirect based on country code:
//if ($var_country_code == "US") {
//    header('HTTP/1.1 301 Moved Permanently');
//    header('Location: https://quatroventos.com.br/en/index');
//    exit;
//} else if ($var_country_code == "GB") {
//    header('HTTP/1.1 301 Moved Permanently');
//    header('Location: https://quatroventos.com.br/en/index');
//    exit;
//}
//
//?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
	
	
    <meta charset="utf-8"/>

    <title>Quatro Ventos - Desenvolvimento de Sites</title>
    <meta name="title" content="Quatro Ventos - Desenvolvimento de Sites">
    <meta name="description" content="Somos uma agência de desenvolvimento de sites ágil, com uma equipe preparada para desenvolver e manter qualquer tipo de projeto web.">

    <meta property="og:title" content="Quatro Ventos - Desenvolvimento de Sites">
    <meta property="og:site_name" content="Quatro Ventos - Desenvolvimento de Sites">
    <meta property="og:url" content="https://www.quatroventos.com.br">
    <meta property="og:description" content="Somos uma agência de desenvolvimento de sites ágil, com uma equipe preparada para desenvolver e manter qualquer tipo de projeto web.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://www.quatroventos.com.br/assets/img/hero-home.png">

    <meta color="#bcd56e" >
    <meta name="theme-color" content="#bcd56e" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#bcd56e">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>

    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">

    <!--     Fonts and icons     -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200|Open+Sans+Condensed:700"
          rel="stylesheet"> -->
     <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">
    <!-- CSS Files -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="./assets/css/now-ui-kit.css?v=1.3.1" rel="stylesheet"/>
    <link href="./assets/owl.carousel.min.css" rel="stylesheet"/>
    <link href="./assets/owl.theme.default.min.css" rel="stylesheet"/>
    <link href="./assets/demo/demo.css" rel="stylesheet"/>
    <link href="./assets/css/fullpage.min.css" rel="stylesheet"/>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
          integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
          crossorigin="anonymous"/> -->
          <!-- FONTE -->
    <link rel="stylesheet" href="./assets/css/fonts.css">

    <link rel="canonical" href="https://www.quatroventos.com.br/" />
</head>

<body class="index-page sidebar-collapse">

<?php include('header.php');
//https://www.canva.com/colors/color-palettes/thwarted-summer-shower/
//https://coolors.co/687175-a4d6eb-eac043-cb682f-090706
?>

<div class="wrapper">

    <div class="page-header header-filter">
        <div class="page-header-image" style="background-color: #bcd56e"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 ml-auto mr-auto text-left" data-aos="fade-right" data-aos-duration="600" style="margin-top:10px;">
                    <h1 class="main-title" style="margin-top:10%;">Design, Code, Test, Repeat...</h1>
                    <h5 class="main-description">Estamos prontos para <strong>criar</strong>, <strong>programar</strong> e <strong>cuidar</strong> do seu site.</h5>
                </div>
                <div class="col-md-6 text-right" data-aos="fade-left" data-aos-duration="600" style="margin-top:10px;">
                    <img src="assets/img/hero-home.png" class="img-hero" alt="Quatro Ventos - Desenvolvimento de sites" >
                </div>
            </div>
        </div>
    </div>


    <!-- quem somos -->
    <div class="section" style="padding:0; background:rgb(235, 248, 254);">
        <br><br><br>
        <div class="quemsomos">
            <div class="col-md-8 mr-auto ml-auto text-center">
                <h2 class="title">Quem somos?</h2>
                <h4 class="description">Somos uma <strong>agência de desenvolvimento de sites</strong> ágil, com uma equipe preparada para
                    desenvolver e manter qualquer tipo de projeto web.</h4>
            </div>
            <div class="container-fluid">
                <div class="row" style="margin-top:58px;">
                    <div class="col-md-3">

                        <div class="card" data-aos="fade-up">
                            <div class="card-body">
                                <div class="description text-center">
                                    <img src="assets/img/icones-servicos/webdesign.png" alt="desenvolvimento-de-sites">
                                    <h5 class="info-title">Web design</h5>
                                    <a href="desenvolvimento-de-sites" class="btn btn-primary btn-round">Conheça</a>
                                </div>
                            </div>
                        </div>

                        <div class="card" data-aos="fade-up" data-aos-delay="100">
                            <div class="card-body">
                                <div class="description text-center">
                                    <img src="assets/img/icones-servicos/desenv-fullstack.png" alt="desenvolvimento full stack">
                                    <h5 class="info-title">Desenv. Full Stack</h5>
                                    <a href="desenvolvimento-full-stack" class="btn btn-primary btn-round">Conheça</a>
                                </div>
                            </div>
                        </div>

                    </div>
                        <div class="col-md-3">

                            <div class="card" data-aos="fade-up" data-aos-delay="100">
                                <div class="card-body">
                                    <div class="description text-center">
                                        <img src="assets/img/icones-servicos/manutencao.png" alt="manutenção de sites">
                                        <h5 class="info-title">Manutenção</h5>
                                        <a href="manutencao-de-sites" class="btn btn-primary btn-round">Conheça</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card" data-aos="fade-up" data-aos-delay="200">
                                <div class="card-body">
                                    <div class="description text-center">
                                        <img src="assets/img/icones-servicos/hosting.png" alt="hospedagem de sites">
                                        <h5 class="info-title">Hosting</h5>
                                        <a href="hospedagem-de-sites" class="btn btn-primary btn-round">Conheça</a>
                                    </div>
                                </div>
                            </div>


                        </div>

                    <div class="col-md-6 hidden-sm d-none d-md-block " data-aos="fade-left" data-aos-delay="300">

                            <img src="assets/img/imac.png" alt="Quatro Ventos - Desenvolvimento de Sites">

                    </div>
                </div>
            </div>

            <!-- clientes -->

            <!-- tecnologias -->
            <div class="section fp-auto-height" style="padding:0; background-color:#e26f6f;">
                <br><br><br>
                <div id="tecnologias" class="features-7" style="padding:30px 0;">
                    <div class="col-md-8 mr-auto ml-auto text-center">
                        <h2 class="title" style="color:white;">Alguns de nossos clientes</h2>
                    </div><br><br>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="owl-carousel owl-theme" data-aos="fade-up" >

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/santaplural.png" alt="Santa Plural" >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/rodolfofontana.png" alt="Rodolfo Fontana">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/liv.png" alt="Liv Decora">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/inoveit.png" alt="Inove IT">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/hemera.png" alt="Hemera DTVM">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/garimpo.png" alt="Garimpo Home">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/fernandasaad.png" alt="Fernanda Saad">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/giselesiqueira.png" alt="Gisele Siqueira Dermatologista">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/digio.png" alt="Digio">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/ddm.png" alt="Data Driven Mkt">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/colmeias.png" alt="Colmeias Design">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/bittencourt.png" alt="Bittencourt Advocacia" >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/aliancafrancesa.png" alt="Aliança Francesa Curitiba">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/digitalhub.png" alt="Digital Hub">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/costibebidas.png" alt="Costi Bebidas">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/yuca.png" alt="Yuca">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/agcapital.png" alt="AG Capital">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/bethenext.png" alt="Be the Next">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/campanelli.png" alt="Campanelli Sano Arquitetura">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/classicotas.png" alt="Classicota">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/conceber.png" alt="Conceber">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/conela.png" alt="Conela">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/cycloar.png" alt="Cycloar">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/domum.png" alt="Domum">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/electra.png" alt="Electra">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/foodcompany.png" alt="Food Company">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/grasp.png" alt="Grasp">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/itecne.png" alt="Itecne">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/ivorecap.png" alt="Ivo Recap">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/madalenasofia.png" alt="Madalena Sofia">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/marista.png" alt="Colégio Marista">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/marykay.png" alt="Mary Kay">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/nummus.png" alt="Nummus">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/plastprime.png" alt="Plast Prime">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/prodigital.png" alt="Pro Digital">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/pucpr.png" alt="Puc-PR">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img src="assets/img/clientes/studiod.png" alt="Studio D">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <br><br><br>
                </div>
            </div>
            <!-- tecnologias -->
            <!-- clientes -->

        </div>
    </div>
    <!-- quem somos -->

    <div class="section" style="padding:0;">
        <?php include('sessao-contato.php'); ?>
    </div>

<?php include('footer.php'); ?>