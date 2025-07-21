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
header("Cache-Control: max-age=31536000"); // HTTP 1.1.
?>

<?php

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


?>
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
    <!--     Fonts and icons     -->
    <!-- <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
    <!-- async CSS -->
    <!-- <link async="" rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');" href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200|Open+Sans+Condensed:700"> -->
    <!-- no-JS fallback -->
    <noscript>
        <!-- <link async="" rel="preload" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200|Open+Sans+Condensed:700"> -->
    </noscript>

    <!-- <link defer="" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <link defer="" rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">
    <!-- Critical CSS -->
    <style>
        .d-none{display:none!important}[data-aos^=fade][data-aos^=fade]{opacity:0}[data-aos=fade-up]{transform:translate3d(0,100px,0)}[data-aos=fade-right]{transform:translate3d(-100px,0,0)}[data-aos=fade-left]{transform:translate3d(100px,0,0)}:root{--blue:#007bff;--indigo:#6610f2;--purple:#6f42c1;--pink:#e83e8c;--red:#dc3545;--orange:#fd7e14;--yellow:#ffc107;--green:#28a745;--teal:#20c997;--cyan:#17a2b8;--white:#fff;--gray:#6c757d;--gray-dark:#343a40;--primary:#007bff;--secondary:#6c757d;--success:#28a745;--info:#17a2b8;--warning:#ffc107;--danger:#dc3545;--light:#f8f9fa;--dark:#343a40;--breakpoint-xs:0;--breakpoint-sm:576px;--breakpoint-md:768px;--breakpoint-lg:992px;--breakpoint-xl:1200px;--font-family-sans-serif:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--font-family-monospace:SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace}*,::after,::before{box-sizing:border-box}html{font-family:sans-serif;line-height:1.15;-webkit-text-size-adjust:100%}nav{display:block}body{margin:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";font-size:1rem;font-weight:400;line-height:1.5;color:#212529;text-align:left;background-color:#fff}h1,h5{margin-top:0;margin-bottom:.5rem}p{margin-top:0;margin-bottom:1rem}ul{margin-top:0;margin-bottom:1rem}strong{font-weight:bolder}a{color:#007bff;text-decoration:none;background-color:transparent}img{vertical-align:middle;border-style:none}button{border-radius:0}button{margin:0;font-family:inherit;font-size:inherit;line-height:inherit}button{overflow:visible}button{text-transform:none}[type=button],button{-webkit-appearance:button}[type=button]::-moz-focus-inner,button::-moz-focus-inner{padding:0;border-style:none}::-webkit-file-upload-button{font:inherit;-webkit-appearance:button}h1,h5{margin-bottom:.5rem;font-weight:500;line-height:1.2}h1{font-size:2.5rem}h5{font-size:1.25rem}.container{width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}@media (min-width:576px){.container{max-width:540px}}@media (min-width:768px){.container{max-width:720px}}@media (min-width:992px){.container{max-width:960px}}@media (min-width:1200px){.container{max-width:1140px}}.row{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}.col-md-6{position:relative;width:100%;padding-right:15px;padding-left:15px}@media (min-width:768px){.col-md-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}}.btn{display:inline-block;font-weight:400;color:#212529;text-align:center;vertical-align:middle;background-color:transparent;border:1px solid transparent;padding:.375rem .75rem;font-size:1rem;line-height:1.5;border-radius:.25rem}.collapse:not(.show){display:none}.nav-link{display:block;padding:.5rem 1rem}.navbar{position:relative;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between;padding:.5rem 1rem}.navbar>.container{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between}.navbar-brand{display:inline-block;padding-top:.3125rem;padding-bottom:.3125rem;margin-right:1rem;font-size:1.25rem;line-height:inherit;white-space:nowrap}.navbar-nav{display:-ms-flexbox;display:flex;-ms-flex-direction:column;flex-direction:column;padding-left:0;margin-bottom:0;list-style:none}.navbar-nav .nav-link{padding-right:0;padding-left:0}.navbar-collapse{-ms-flex-preferred-size:100%;flex-basis:100%;-ms-flex-positive:1;flex-grow:1;-ms-flex-align:center;align-items:center}.navbar-toggler{padding:.25rem .75rem;font-size:1.25rem;line-height:1;background-color:transparent;border:1px solid transparent;border-radius:.25rem}@media (max-width:991.98px){.navbar-expand-lg>.container{padding-right:0;padding-left:0}}@media (min-width:992px){.navbar-expand-lg{-ms-flex-flow:row nowrap;flex-flow:row nowrap;-ms-flex-pack:start;justify-content:flex-start}.navbar-expand-lg .navbar-nav{-ms-flex-direction:row;flex-direction:row}.navbar-expand-lg .navbar-nav .nav-link{padding-right:.5rem;padding-left:.5rem}.navbar-expand-lg>.container{-ms-flex-wrap:nowrap;flex-wrap:nowrap}.navbar-expand-lg .navbar-collapse{display:-ms-flexbox!important;display:flex!important;-ms-flex-preferred-size:auto;flex-basis:auto}.navbar-expand-lg .navbar-toggler{display:none}}.card{position:relative;display:-ms-flexbox;display:flex;-ms-flex-direction:column;flex-direction:column;min-width:0;word-wrap:break-word;background-color:#fff;background-clip:border-box;border:1px solid rgba(0,0,0,.125);border-radius:.25rem}.card-body{-ms-flex:1 1 auto;flex:1 1 auto;padding:1.25rem}.bg-white{background-color:#fff!important}.fixed-top{position:fixed;top:0;right:0;left:0;z-index:1030}.mr-auto{margin-right:auto!important}.ml-auto{margin-left:auto!important}.text-left{text-align:left!important}.text-right{text-align:right!important}button{font-family:Montserrat,"Helvetica Neue",Arial,sans-serif}h1,h5{font-weight:400}a{color:#f96332}h1{font-size:3.5em;line-height:1.15;margin-bottom:30px}h5{font-size:1.3em;line-height:1.4em;margin-bottom:15px}p{line-height:1.61em;font-weight:300;font-size:1.2em}body{color:#2c2c2c;font-size:14px;font-family:Montserrat,"Helvetica Neue",Arial,sans-serif;overflow-x:hidden;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased}.btn{border-width:2px;font-weight:400;font-size:1.3em;line-height:1.35em;margin:10px 1px;border:none;border-radius:.1875rem;padding:11px 22px;background-color:#888;color:#fff}.navbar{padding-top:.625rem;padding-bottom:.625rem;min-height:53px;margin-bottom:20px;box-shadow:0 0 20px 0 rgba(0,0,0,.15)}.navbar a{vertical-align:middle}.navbar a:not(.btn):not(.dropdown-item){color:#fff}.navbar p{display:inline-block;margin:0;line-height:21px;font-weight:inherit;font-size:inherit}.navbar .navbar-nav .nav-link.btn{padding:11px 22px}.navbar .navbar-nav .nav-link:not(.btn){text-transform:uppercase;font-size:.7142em;padding:.5rem .7rem;line-height:1.625rem}.navbar .navbar-brand{text-transform:uppercase;font-size:.8571em;padding-top:.5rem;padding-bottom:.5rem;line-height:1.625rem}.navbar .navbar-toggler{width:37px;height:27px;outline:0}.navbar .navbar-toggler .navbar-toggler-bar.middle-bar{width:17px}.navbar.navbar-transparent{background-color:transparent!important;box-shadow:none;color:#fff;padding-top:20px!important}.bg-white{background-color:#2c2e3a!important;height:70px}.navbar p{line-height:1.8em;font-size:1em;font-weight:400}.navbar .navbar-toggler{vertical-align:middle}img{max-width:100%;border-radius:1px}.card{border:0;border-radius:.1875rem;display:inline-block;position:relative;width:100%;margin-bottom:30px;box-shadow:0 5px 25px 0 rgba(0,0,0,.2)}.index-page .page-header{height:125vh}.page-header{min-height:100vh;max-height:999px;padding:0;color:#fff;position:relative;overflow:hidden}.page-header:before{background-color:rgba(0,0,0,.3)}.page-header>.container{z-index:2;padding-top:12vh;padding-bottom:40px}.page-header .page-header-image{position:absolute;background-size:cover;background-position:center center;width:100%;height:100%;z-index:-1}.page-header .container{height:100%;z-index:1;text-align:center;position:relative}.page-header .container{color:#fff}.header-filter{position:relative}.header-filter .container{z-index:2;position:relative}@media screen and (max-width:991px){.navbar-collapse{position:fixed;display:block!important;top:0;height:100%!important;width:300px;right:0;z-index:1032;visibility:visible;background-color:#999;overflow-y:visible;border-top:none;text-align:left;max-height:none!important;-webkit-transform:translate3d(300px,0,0);-moz-transform:translate3d(300px,0,0);-o-transform:translate3d(300px,0,0);-ms-transform:translate3d(300px,0,0);transform:translate3d(300px,0,0)}.navbar-collapse:before{background:#000;opacity:.76;display:block;content:"";position:absolute;width:100%;height:100%;top:0;left:0;z-index:-1}.navbar-collapse .navbar-nav:not(.navbar-logo) .nav-link{margin:0 1rem;margin-top:.3125rem}.navbar-collapse .navbar-nav:not(.navbar-logo) .nav-link:not(.btn){color:#fff!important}.navbar .navbar-nav{margin-top:53px;position:relative;max-height:calc(100vh - 75px);min-height:100%;overflow:auto}.navbar .navbar-translate{width:100%;position:relative;display:flex;-ms-flex-pack:justify!important;justify-content:space-between!important;-ms-flex-align:center;align-items:center;-webkit-transform:translate3d(0,0,0);-moz-transform:translate3d(0,0,0);-o-transform:translate3d(0,0,0);-ms-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}.navbar .navbar-toggler-bar{display:block;position:relative;width:22px;height:1px;border-radius:1px;background:#fff}.navbar .navbar-toggler-bar+.navbar-toggler-bar{margin-top:7px}.top-bar{transform:rotate(0)}.middle-bar{opacity:1}.bottom-bar{transform:rotate(0)}[class*=navbar-expand-] .navbar-collapse{width:300px}}@media screen and (min-width:992px){.navbar-nav .nav-link.btn:not(.btn-sm){margin:0}.navbar-nav .nav-item:not(:last-child){margin-right:5px}.navbar-collapse:not(.has-image){background:0 0!important}}.btn-white{background:0 0;border:1px solid #fff;color:#fff}.main-title{font-weight:bolder;font-size:12vw;line-height:12vw;width:100%;margin-top:100px;text-align:center}.main-description{font-size:5vw;margin-top:-15px;width:100%;text-align:center}@media only screen and (min-width:768px){.main-title{font-weight:bolder;font-size:80px;margin-top:50%;line-height:75px;width:auto;text-align:left}.main-description{font-size:30px;margin-top:-20px;width:auto;text-align:left}}#tecnologias .icon{font-size:40px}#tecnologias .card{text-align:center}.owl-carousel{position:relative}.owl-carousel{display:none;width:100%;z-index:1}.index-page .page-header{background:0 0}@media (max-width:768px){.btn{margin-bottom:10px}}
    </style>
<!-- FONTE -->
<link rel="stylesheet" href="./assets/css/fonts.css">
    <link rel="canonical" href="https://www.quatroventos.com.br/" />

    <style>
        .icone {
        width: 1em;
        height: 1em;
        vertical-align: -0.125em;
        }
        .icone2{
            width: 29px;
            height: 29px;
            margin: auto;
            
        }
        .page-header .container, .index-page .page-header, .presentation-page .page-header{
            height: auto !important;
        }
        .page-header{
            min-height: auto;
            padding-bottom: 80px;
        }
        .index-page .page-header, .presentation-page .page-header{
            height: auto;
        }

        @media (max-width: 730px) {
            .main-title{
                margin-top:50px;
            }
            .header--cta{
                display: block;
                align-items: center;
                margin-top:30px;
            }
            .img-hero{
                margin:auto;
                margin-top: -80px;
                height: 300px;
                align-items: center;
                display: flex;
            }
        }

    /*  timeline*/
        .timeline-with-icons {
            border-left: 1px solid hsl(0, 0%, 90%);
            position: relative;
            list-style: none;
        }

        .timeline-with-icons .timeline-item {
            position: relative;
        }

        .timeline-with-icons .timeline-item:after {
            position: absolute;
            display: block;
            top: 0;
        }

        .timeline-with-icons .timeline-icon {
            position: absolute;
            left: -48px;
            background-color: hsl(217, 88.2%, 90%);
            color: hsl(217, 88.8%, 35.1%);
            border-radius: 50%;
            height: 31px;
            width: 31px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

    </style>



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
                <div class="col-md-6 ml-auto mr-auto text-left" style="margin-top:10px;">
                    <h1 class="main-title" style="margin-top:10%;">Design, Code, Test, Repeat...</h1>
                    <h5 class="main-description">Desenhar, programar, testar, repetir.</h5>
                    <p><strong>Esse é nosso lema. Ele foi inspirado no algoritmo do shampoo, uma brincadeira na área de ti baseada nas instruções das embalagens de shampoo (lave, enxágue e repita), o que gera um loop infinito.
                            Para nós o loop está em manter os sites de nossos clientes sempre atualizados e funcionando corretamente, é um trabalho sem fim e que exige muita dedicação e carinho, e que amamos fazer.</strong></p>
<!--                    <a href="https://api.whatsapp.com/send?l=pt-BR&phone=554130399593" class="btn header--cta btn-primary btn-round">Faça um orçamento sem compromisso!</a>-->
<!--                    <a href="https://api.whatsapp.com/send?l=pt-BR&phone=554130399593" class="btn header--cta btn-round" style="background: #25D366;" target='_blank'><i class="fa-brands fa-whatsapp"></i> Fale com um especialista!</a>-->
                </div>
                <div class="col-md-6 text-right"  style="margin-top:10px;">
                    <img src="assets/img/hero-home.png" class="img-hero" alt="Quatro Ventos - Desenvolvimento de sites" >
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center" style="font-size:35px;"  data-aos="fade-down" data-aos-delay="800">
                    <a href="#">
                        <div id="mouse-scroll">
                            <div class="mouse">
                                <div class="mouse-in"></div>
                            </div>
                            <div class="arrows">
                                <span class="down-arrow-1"></span>
                                <span class="down-arrow-2"></span>
                                <span class="down-arrow-3"></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>


    <!-- quem somos -->
    <div class="section" style="padding:0; background:rgb(235, 248, 254);">
        <br><br><br>
        <div class="quemsomos">
            <div class="col-md-8 mr-auto ml-auto text-center">
                <h2 class="title">Desde 2005</h2>
                <h4 class="description">
                    Nossa história se mistura com a história da internet, nosso fundador Gabriel, começou a programar sites em 1998, quando era "tudo mirc".
                    Em 2005 nasceu a Quatro Ventos, uma agência com vocação para ser digital.
                </h4>
            </div>

            <div class="container-fluid">

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">



                        <!-- Section: Timeline -->
                        <section class="py-5">
                            <ul class="timeline-with-icons">
                                <li class="timeline-item mb-5">
      <span class="timeline-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#f96332" width="14px" height="14px"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M156.6 384.9L125.7 354c-8.5-8.5-11.5-20.8-7.7-32.2c3-8.9 7-20.5 11.8-33.8L24 288c-8.6 0-16.6-4.6-20.9-12.1s-4.2-16.7 .2-24.1l52.5-88.5c13-21.9 36.5-35.3 61.9-35.3l82.3 0c2.4-4 4.8-7.7 7.2-11.3C289.1-4.1 411.1-8.1 483.9 5.3c11.6 2.1 20.6 11.2 22.8 22.8c13.4 72.9 9.3 194.8-111.4 276.7c-3.5 2.4-7.3 4.8-11.3 7.2l0 82.3c0 25.4-13.4 49-35.3 61.9l-88.5 52.5c-7.4 4.4-16.6 4.5-24.1 .2s-12.1-12.2-12.1-20.9l0-107.2c-14.1 4.9-26.4 8.9-35.7 11.9c-11.2 3.6-23.4 .5-31.8-7.8zM384 168a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
      </span>

                                    <h5 class="fw-bold">Our company starts its operations</h5>
                                    <p class="text-muted mb-2 fw-bold">11 March 2020</p>
                                    <p class="text-muted">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit
                                        necessitatibus adipisci, ad alias, voluptate pariatur officia
                                        repellendus repellat inventore fugit perferendis totam dolor
                                        voluptas et corrupti distinctio maxime corporis optio?
                                    </p>
                                </li>

                                <li class="timeline-item mb-5">

      <span class="timeline-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="#f96332" width="14px" height="14px"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M312 24l0 10.5c6.4 1.2 12.6 2.7 18.2 4.2c12.8 3.4 20.4 16.6 17 29.4s-16.6 20.4-29.4 17c-10.9-2.9-21.1-4.9-30.2-5c-7.3-.1-14.7 1.7-19.4 4.4c-2.1 1.3-3.1 2.4-3.5 3c-.3 .5-.7 1.2-.7 2.8c0 .3 0 .5 0 .6c.2 .2 .9 1.2 3.3 2.6c5.8 3.5 14.4 6.2 27.4 10.1l.9 .3s0 0 0 0c11.1 3.3 25.9 7.8 37.9 15.3c13.7 8.6 26.1 22.9 26.4 44.9c.3 22.5-11.4 38.9-26.7 48.5c-6.7 4.1-13.9 7-21.3 8.8l0 10.6c0 13.3-10.7 24-24 24s-24-10.7-24-24l0-11.4c-9.5-2.3-18.2-5.3-25.6-7.8c-2.1-.7-4.1-1.4-6-2c-12.6-4.2-19.4-17.8-15.2-30.4s17.8-19.4 30.4-15.2c2.6 .9 5 1.7 7.3 2.5c13.6 4.6 23.4 7.9 33.9 8.3c8 .3 15.1-1.6 19.2-4.1c1.9-1.2 2.8-2.2 3.2-2.9c.4-.6 .9-1.8 .8-4.1l0-.2c0-1 0-2.1-4-4.6c-5.7-3.6-14.3-6.4-27.1-10.3l-1.9-.6c-10.8-3.2-25-7.5-36.4-14.4c-13.5-8.1-26.5-22-26.6-44.1c-.1-22.9 12.9-38.6 27.7-47.4c6.4-3.8 13.3-6.4 20.2-8.2L264 24c0-13.3 10.7-24 24-24s24 10.7 24 24zM568.2 336.3c13.1 17.8 9.3 42.8-8.5 55.9L433.1 485.5c-23.4 17.2-51.6 26.5-80.7 26.5L192 512 32 512c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l36.8 0 44.9-36c22.7-18.2 50.9-28 80-28l78.3 0 16 0 64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0-16 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l120.6 0 119.7-88.2c17.8-13.1 42.8-9.3 55.9 8.5zM193.6 384c0 0 0 0 0 0l-.9 0c.3 0 .6 0 .9 0z"/></svg>
      </span>
                                    <h5 class="fw-bold">First customer</h5>
                                    <p class="text-muted mb-2 fw-bold">19 March 2020</p>
                                    <p class="text-muted">
                                        Quisque ornare dui nibh, sagittis egestas nisi luctus nec. Sed
                                        aliquet laoreet sapien, eget pulvinar lectus maximus vel.
                                        Phasellus suscipit porta mattis.
                                    </p>
                                </li>

                                <li class="timeline-item mb-5">

      <span class="timeline-icon">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" fill="#f96332" width="14px" height="14px"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z"/></svg>
      </span>
                                    <h5 class="fw-bold">Our team exceeds 10 people</h5>
                                    <p class="text-muted mb-2 fw-bold">24 June 2020</p>
                                    <p class="text-muted">
                                        Orci varius natoque penatibus et magnis dis parturient montes,
                                        nascetur ridiculus mus. Nulla ullamcorper arcu lacus, maximus
                                        facilisis erat pellentesque nec. Duis et dui maximus dui aliquam
                                        convallis. Quisque consectetur purus erat, et ullamcorper sapien
                                        tincidunt vitae.
                                    </p>
                                </li>

                                <li class="timeline-item mb-5">

      <span class="timeline-icon">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="#f96332" width="14px" height="14px"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 112.5L0 422.3c0 18 10.1 35 27 41.3c87 32.5 174 10.3 261-11.9c79.8-20.3 159.6-40.7 239.3-18.9c23 6.3 48.7-9.5 48.7-33.4l0-309.9c0-18-10.1-35-27-41.3C462 15.9 375 38.1 288 60.3C208.2 80.6 128.4 100.9 48.7 79.1C25.6 72.8 0 88.6 0 112.5zM288 352c-44.2 0-80-43-80-96s35.8-96 80-96s80 43 80 96s-35.8 96-80 96zM64 352c35.3 0 64 28.7 64 64l-64 0 0-64zm64-208c0 35.3-28.7 64-64 64l0-64 64 0zM512 304l0 64-64 0c0-35.3 28.7-64 64-64zM448 96l64 0 0 64c-35.3 0-64-28.7-64-64z"/></svg>
      </span>
                                    <h5 class="fw-bold">Earned the first million $!</h5>
                                    <p class="text-muted mb-2 fw-bold">15 October 2020</p>
                                    <p class="text-muted">
                                        Nulla ac tellus convallis, pulvinar nulla ac, fermentum diam. Sed
                                        et urna sit amet massa dapibus tristique non finibus ligula. Nam
                                        pharetra libero nibh, id feugiat tortor rhoncus vitae. Ut suscipit
                                        vulputate mattis.
                                    </p>
                                </li>
                            </ul>
                        </section>
                        <!-- Section: Timeline -->

                        
                    </div>
                </div>
            </div>

<!--            <div class="container-fluid">-->
<!--                <div class="row" style="margin-top:58px;">-->
<!--                    <div class="col-md-3">-->
<!---->
<!--                        <div class="" data-aos="fade-up">-->
<!--                            <div class="card-body">-->
<!--                                <div class="description text-center">-->
<!--                                    <img data-src="assets/img/icones-servicos/webdesign.png" class="lazy" alt="desenvolvimento-de-sites">-->
<!--                                    <h5 class="info-title">Desenvolvimento de sites</h5>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!---->
<!--                        <div class="" data-aos="fade-up" data-aos-delay="100">-->
<!--                            <div class="card-body">-->
<!--                                <div class="description text-center">-->
<!--                                    <img data-src="assets/img/icones-servicos/desenv-fullstack.png" class="lazy" alt="desenvolvimento full stack">-->
<!--                                    <h5 class="info-title">Desenvolvimento de aplicações</h5>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!---->
<!--                    </div>-->
<!--                    <div class="col-md-3">-->
<!---->
<!--                        <div class="" data-aos="fade-up" data-aos-delay="100">-->
<!--                            <div class="card-body">-->
<!--                                <div class="description text-center">-->
<!--                                    <img data-src="assets/img/icones-servicos/manutencao.png" class="lazy" alt="manutenção de sites">-->
<!--                                    <h5 class="info-title">Manutenção de sites</h5>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!---->
<!--                        <div class="" data-aos="fade-up" data-aos-delay="200">-->
<!--                            <div class="card-body">-->
<!--                                <div class="description text-center">-->
<!--                                    <img data-src="assets/img/icones-servicos/hosting.png" class="lazy" alt="hospedagem de sites">-->
<!--                                    <h5 class="info-title">Hospedagem de sites</h5>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!---->
<!---->
<!--                    </div>-->
<!---->
<!--                    <div class="col-md-6 hidden-sm d-none d-md-block " data-aos="fade-left" data-aos-delay="200">-->
<!---->
<!--                        <img data-src="assets/img/imac.png" class="lazy" alt="Quatro Ventos - Desenvolvimento de Sites">-->
<!---->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

            <!-- serviços -->

            <!--            <div class="section" style="padding:0; background:rgb(235, 248, 254);">-->
            <!--                <br><br><br>-->
            <!--                <div class="manutencao">-->
            <!--                    <div class="col-md-8 mr-auto ml-auto text-center">-->
            <!--                        <h2 class="title">O que podemos fazer por você?</h2>-->
            <!--                    </div>-->
            <!--                    <br>-->
            <!--                    <div class="container">-->
            <!--                        <div class="row">-->
            <!--                            <div class="col-sm-12 col-md-3">-->
            <!--                                <div class="card" data-aos="fade-up">-->
            <!--                                    <div class="card-body">-->
            <!--                                        <div class="description text-center">-->
            <!--                                            <img src="assets/img/icones-servicos/webdesign.png" class="lazy" alt="desenvolvimento-de-sites">-->
            <!--                                            <h5 class="info-title">Desenvolvimento de sites</h5>-->
            <!--                                        </div>-->
            <!--                                    </div>-->
            <!--                                </div>-->
            <!--                            </div>-->
            <!--                            <div class="col-sm-12 col-md-3">-->
            <!--                                <div class="card" data-aos="fade-up" data-aos-delay="100">-->
            <!--                                    <div class="card-body">-->
            <!--                                        <div class="description text-center">-->
            <!--                                            <img src="assets/img/icones-servicos/desenv-fullstack.png" class="lazy" alt="desenvolvimento full stack">-->
            <!--                                            <h5 class="info-title">Desenvolvimento de aplicações</h5>-->
            <!--                                        </div>-->
            <!--                                    </div>-->
            <!--                                </div>-->
            <!--                            </div>-->
            <!--                            <div class="col-sm-12 col-md-3">-->
            <!--                                <div class="card" data-aos="fade-up" data-aos-delay="200">-->
            <!--                                    <div class="card-body">-->
            <!--                                        <div class="description text-center">-->
            <!--                                            <img src="assets/img/icones-servicos/manutencao.png" alt="manutenção de sites">-->
            <!--                                            <h5 class="info-title">Manutenção de sites<br><br></h5>-->
            <!--                                        </div>-->
            <!--                                    </div>-->
            <!--                                </div>-->
            <!--                            </div>-->
            <!--                            <div class="col-sm-12 col-md-3">-->
            <!--                                <div class="card" data-aos="fade-up" data-aos-delay="200">-->
            <!--                                    <div class="card-body">-->
            <!--                                        <div class="description text-center">-->
            <!--                                            <img src="assets/img/icones-servicos/hosting.png" class="lazy" alt="hospedagem de sites">-->
            <!--                                            <h5 class="info-title">Hospedagem de sites<br><br></h5>-->
            <!--                                        </div>-->
            <!--                                    </div>-->
            <!--                                </div>-->
            <!--                            </div>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                    <br><br><br>-->
            <!--                </div>-->
            <!--            </div>-->

            <!-- serviços -->

            <!--metodolgia-->

            <div class="section fp-auto-height" style="padding:0; background-color:#3694D1;">
                <br><br><br>

                <div class="col-md-8 mr-auto ml-auto text-center">
                    <h2 class="title" style="color:white;">Nossa Metodologia</h2>
                    <h4 class="description" style="color:white;">
                        Todos os nossos projetos de <strong>desenvolvimento de sites</strong> passam por algumas etapas para garantir que você fique satisfeito com o produto final.
                    </h4>
                </div>

                <div class="container metodologia">
            <div class="row" style="margin-top:58px;">

                <div class="info info-horizontal" data-aos="fade-up">
                    <div class="icon icon-primary icon-circle d-flex" style="color:#B4DB5C">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="icone2" fill="#B4DB5C"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M208 80c0-26.5 21.5-48 48-48l64 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-8 0 0 40 152 0c30.9 0 56 25.1 56 56l0 32 8 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-64 0c-26.5 0-48-21.5-48-48l0-64c0-26.5 21.5-48 48-48l8 0 0-32c0-4.4-3.6-8-8-8l-152 0 0 40 8 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-64 0c-26.5 0-48-21.5-48-48l0-64c0-26.5 21.5-48 48-48l8 0 0-40-152 0c-4.4 0-8 3.6-8 8l0 32 8 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-64 0c-26.5 0-48-21.5-48-48l0-64c0-26.5 21.5-48 48-48l8 0 0-32c0-30.9 25.1-56 56-56l152 0 0-40-8 0c-26.5 0-48-21.5-48-48l0-64z"/></svg>
                    </div>
                    <div class="description">
                        <h4 class="info-title">Arquitetura</h4>
                        <p>Nosso primeiro passo é estudar o fluxo de navegação do usuário..</p>
                    </div>
                </div>

                <div class="info info-horizontal" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon icon-primary icon-circle d-flex" style="color:#F9A902">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icone2" fill="#F9A902"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4 .4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"/></svg>
                    </div>
                    <div class="description">
                        <h4 class="info-title">Prototipagem</h4>
                        <p>Após validado o fluxo, desenhamos os protótipos das telas do site.</p>
                    </div>
                </div>

                <div class="info info-horizontal" data-aos="fade-up" data-aos-delay="400">
                    <div class="icon icon-primary icon-circle d-flex" style="color:#FE6263">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="icone2" fill="#FE6263"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M392.8 1.2c-17-4.9-34.7 5-39.6 22l-128 448c-4.9 17 5 34.7 22 39.6s34.7-5 39.6-22l128-448c4.9-17-5-34.7-22-39.6zm80.6 120.1c-12.5 12.5-12.5 32.8 0 45.3L562.7 256l-89.4 89.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l112-112c12.5-12.5 12.5-32.8 0-45.3l-112-112c-12.5-12.5-32.8-12.5-45.3 0zm-306.7 0c-12.5-12.5-32.8-12.5-45.3 0l-112 112c-12.5 12.5-12.5 32.8 0 45.3l112 112c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256l89.4-89.4c12.5-12.5 12.5-32.8 0-45.3z"/></svg>
                    </div>
                    <div class="description">
                        <h4 class="info-title">Frontend</h4>
                        <p>Com base nas telas desenhadas, programamos a parte visual do site.</p>
                    </div>
                </div>

                <div class="info info-horizontal" data-aos="fade-up" data-aos-delay="600">
                    <div class="icon icon-primary icon-circle d-flex" style="color:#0F91D5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icone2" fill="#0F91D5"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M448 80l0 48c0 44.2-100.3 80-224 80S0 172.2 0 128L0 80C0 35.8 100.3 0 224 0S448 35.8 448 80zM393.2 214.7c20.8-7.4 39.9-16.9 54.8-28.6L448 288c0 44.2-100.3 80-224 80S0 332.2 0 288L0 186.1c14.9 11.8 34 21.2 54.8 28.6C99.7 230.7 159.5 240 224 240s124.3-9.3 169.2-25.3zM0 346.1c14.9 11.8 34 21.2 54.8 28.6C99.7 390.7 159.5 400 224 400s124.3-9.3 169.2-25.3c20.8-7.4 39.9-16.9 54.8-28.6l0 85.9c0 44.2-100.3 80-224 80S0 476.2 0 432l0-85.9z"/></svg>
                    </div>
                    <div class="description">
                        <h4 class="info-title">Backend</h4>
                        <p>Enquanto trabalhamos no frontend, a parte funcional do site é programada.</p>
                    </div>
                </div>

                <div class="info info-horizontal" data-aos="fade-up" data-aos-delay="800">
                    <div class="icon icon-primary icon-circle d-flex" style="color:#37325E">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="icone2" fill="#37325E"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>
                    </div>
                    <div class="description">
                        <h4 class="info-title">Homologação</h4>
                        <p>Finalizadas as etapas anteriores, publicamos o site em um local privado para que seja avaliado.</p>
                    </div>
                </div>

                <div class="info info-horizontal" data-aos="fade-up" data-aos-delay="1000">
                    <div class="icon icon-primary icon-circle d-flex" style="color:#FF29A0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icone2" fill="#FF29A0"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M288 109.3L288 352c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-242.7-73.4 73.4c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l128-128c12.5-12.5 32.8-12.5 45.3 0l128 128c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L288 109.3zM64 352l128 0c0 35.3 28.7 64 64 64s64-28.7 64-64l128 0c35.3 0 64 28.7 64 64l0 32c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64l0-32c0-35.3 28.7-64 64-64zM432 456a24 24 0 1 0 0-48 24 24 0 1 0 0 48z"/></svg>
                    </div>
                    <div class="description">
                        <h4 class="info-title">Entrega</h4>
                        <p>Quando tudo estiver testado e aprovado, será publicado no endereço final. </p>
                    </div>
                </div>


            </div>
            <div class="row" style="margin-top:50px;" >
                <div class="col-md-12 text-center" data-aos="fade-up" data-aos-delay="1100">
                    <p style="font-size:15px">*Antes da publicação, cada página do site passa por um checklist que inclui:<br>
                        Revisão dos links, funcionalidades, testes em smartphones e navegadores.</p>
                </div>
            </div>

            <div class="row" data-aos="fade-up" data-aos-delay="350">
                <div class="col-md-12 text-center">
                    <a href="https://api.whatsapp.com/send?l=pt-BR&phone=554130399593" target="_blank" class="btn btn-primary btn-round" style="margin:auto;">Entre em contato para orçar seu projeto.</a>
                </div>
            </div>

            <br><br><br>
        </div>
            </div>
            <!--metodologia-->


            <!-- funcionalidades -->
            <div class="section" style="padding:0;" id="pontuais">
                <br><br><br>
                <div class="manutencao">
                    <div class="col-md-8 mr-auto ml-auto text-center">
                        <h2 class="title">Como são nossos sites?</h2>
                        <h4 class="description">Cada projeto é único, mas todos têm algumas características em comum.</h4>
                    </div>
                    <br>
                    <div class="container">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="info info-horizontal" data-aos="fade-left" data-aos-delay="0">
                                    <div class="icon">
                                        <i class="now-ui-icons tech_mobile"></i>
                                    </div>
                                    <div class="description">
                                        <h4 class="info-title" >Responsivos</h4>
                                        <p>Todos os nossos sites são responsivos, isso quer dizer que não importa o tamanho da tela: celular, desktop, tablet, nossos sites ficam bem em qualquer uma delas.</p>
                                    </div>
                                </div>
                                <div class="info info-horizontal" data-aos="fade-right" data-aos-delay="100">
                                    <div class="icon">
                                        <i class="now-ui-icons gestures_tap-01"></i>
                                    </div>
                                    <div class="description">
                                        <h4 class="info-title">Gerenciáveis</h4>
                                        <p>Você pode editar todo o conteúdo do seu site através do painel de controle.</p>
                                    </div>
                                </div>

                                <div class="info info-horizontal" data-aos="fade-left" data-aos-delay="200">
                                    <div class="icon">
                                        <i class="now-ui-icons objects_spaceship"></i>
                                    </div>
                                    <div class="description">
                                        <h4 class="info-title">Otimizados para Velocidade</h4>
                                        <p>Otimizamos os arquivos e códigos para que nossos sites carreguem rapidamente e tenha o melhor desempenho possível.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300" style="margin-top:150px;">
                                <div class="phone-container">
                                    <img data-src="assets/img/desenv-fullstack-hero.png" class="lazy" alt="desenvolvimento de sites">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="info info-horizontal" data-aos="fade-right" data-aos-delay="0">
                                    <div class="icon">
                                        <i class="now-ui-icons design-2_ruler-pencil"></i>
                                    </div>
                                    <div class="description">
                                        <h4 class="info-title">Sob medida</h4>
                                        <p>Não cortamos caminho, seu projeto é criado exclusivamente para você, sem templates prontos.</p>
                                    </div>
                                </div>
                                <div class="info info-horizontal" data-aos="fade-left" data-aos-delay="100">
                                <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488 512" class="icone2" fill="#888"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"/></svg>
                            </div>
                                    <div class="description">
                                        <h4 class="info-title">Preparados para o Google</h4>
                                        <p>Deixamos o seu site preparado para a indexação no Google, seguindo todas as diretrizes necessárias para que ele seja entre no índice o quanto antes.</p>
                                    </div>
                                </div>
                                <div class="info info-horizontal" data-aos="fade-right" data-aos-delay="200">
                                    <div class="icon">
                                        <i class="now-ui-icons objects_umbrella-13"></i>
                                    </div>
                                    <div class="description">
                                        <h4 class="info-title">Garantidos</h4>
                                        <p>Após a entrega, você tem uma garantia de 30 dias para eventuais alterações e correções que precisar.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" data-aos="fade-up" data-aos-delay="350">
                            <div class="col-md-12 text-center">
                                <a href="#contato" class="btn btn-primary btn-round" style="margin:auto;">Entre em contato para orçar seu projeto.</a>
                            </div>
                        </div>
                    </div>
                    <br><br><br>
                </div>
            </div>
            <!-- funcionalidades -->


            <!-- portfolio -->
            <div class="section portfolio" style="padding:0; background:rgb(235, 248, 254);">
                <br><br><br>

                <div class="col-md-8 mr-auto ml-auto text-center">
                    <h2 class="title">Conheça nosso trabalho</h2>
                    <h4 class="description">Veja alguns dos projetos de desenvolvimento que executamos. </h4>
                    
                </div><br><br><br>

                <div id="portfolio-carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">

                        <li data-target="#portfolio-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="1"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="2"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="3"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="4"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="5"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="6"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="7"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="8"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="9"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="10"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="11"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="12"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="13"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="14"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="15"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="16"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="17"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="18"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="19"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="20"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="21"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="22"></li>
                        <li data-target="#portfolio-carousel" data-slide-to="23"></li>


                    </ol>

                    <div class="carousel-inner" style="box-shadow: none;">

                        <?php include('portfolio.php'); ?>

                    </div><!-- /carousel-inner -->

                    <!-- Left and right controls -->
                    <a class="carousel-control-prev" href="#portfolio-carousel" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#portfolio-carousel" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>

                </div>
            </div>

            <!-- clientes -->
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
                                                <img data-src="assets/img/clientes/santaplural.png" class="lazy" alt="Santa Plural" >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/rodolfofontana.png" class="lazy" alt="Rodolfo Fontana">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/liv.png" class="lazy" alt="Liv Decora">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/inoveit.png" class="lazy" alt="Inove IT">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/hemera.png" class="lazy" alt="Hemera DTVM">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/garimpo.png" class="lazy" alt="Garimpo Home">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/fernandasaad.png" class="lazy" alt="Fernanda Saad">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/giselesiqueira.png" class="lazy" alt="Gisele Siqueira Dermatologista">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/digio.png" class="lazy" alt="Digio">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/ddm.png" class="lazy" alt="Data Driven Mkt">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/colmeias.png" class="lazy" alt="Colmeias Design">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/bittencourt.png" class="lazy" alt="Bittencourt Advocacia" >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/aliancafrancesa.png" class="lazy" alt="Aliança Francesa Curitiba">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/digitalhub.png" class="lazy" alt="Digital Hub">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/costibebidas.png" class="lazy" alt="Costi Bebidas">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/yuca.png" class="lazy" alt="Yuca">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/agcapital.png" class="lazy" alt="AG Capital">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/bethenext.png" class="lazy" alt="Be the Next">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/campanelli.png" class="lazy" alt="Campanelli Sano Arquitetura">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/classicotas.png" class="lazy" alt="Classicota">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/conceber.png" class="lazy" alt="Conceber">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/conela.png" class="lazy" alt="Conela">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/cycloar.png" class="lazy" alt="Cycloar">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/domum.png" class="lazy" alt="Domum">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/electra.png" class="lazy" alt="Electra">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/foodcompany.png" class="lazy" alt="Food Company">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/grasp.png" class="lazy"  alt="Grasp">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/itecne.png" class="lazy" alt="Itecne">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/ivorecap.png" class="lazy" alt="Ivo Recap">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/madalenasofia.png" class="lazy" alt="Madalena Sofia">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/marista.png" class="lazy" alt="Colégio Marista">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/marykay.png" class="lazy" alt="Mary Kay">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/nummus.png" class="lazy" alt="Nummus">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/plastprime.png" class="lazy" alt="Plast Prime">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/prodigital.png" class="lazy" alt="Pro Digital">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/pucpr.png" class="lazy" alt="Puc-PR">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="icon">
                                                <img data-src="assets/img/clientes/studiod.png" class="lazy" alt="Studio D">
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

    <!-- CSS Files -->
    <link rel="preload" href="https://unpkg.com/aos@2.3.1/dist/aos.css" type="text/css" as="style" onload="this.onload = null; this.rel = 'stylesheet' ;">
    <link rel="preload" href="./assets/css/bootstrap.min.css" type="text/css" as="style" onload="this.onload = null; this.rel = 'stylesheet' ;"/>
    <link rel="preload" href="./assets/css/now-ui-kit.css?v=1.3.1" type="text/css" as="style" onload="this.onload = null; this.rel = 'stylesheet' ;"/>
    <link rel="preload" href="./assets/owl.carousel.min.css" type="text/css" as="style" onload="this.onload = null; this.rel = 'stylesheet' ;"/>
    <link rel="preload" href="./assets/owl.theme.default.min.css" type="text/css" as="style" onload="this.onload = null; this.rel = 'stylesheet' ;"/>
    <link rel="preload" href="./assets/demo/demo.css" type="text/css" as="style" onload="this.onload = null; this.rel = 'stylesheet' ;"/>
    <link rel="preload" href="./assets/css/fullpage.min.css" type="text/css" as="style" onload="this.onload = null; this.rel = 'stylesheet' ;"/>

    <div class="section" style="padding:0;">
        <?php include('sessao-contato.php'); ?>
    </div>

<?php include('footer.php'); ?>