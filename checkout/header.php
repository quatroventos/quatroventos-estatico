<?php include('config.php'); ?>
<!doctype html>
<html lang="pt-br">
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

    <meta color="#31355C" >
    <meta name="theme-color" content="#31355C" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#31355C">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>

    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">


    <!-- choice js -->
    <script src="assets/scripts/choices.min.js"></script>
    <link href="assets/styles/base.css" rel="stylesheet">
    <link href="assets/styles/choices.css" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/dist/css/styles.css" rel="stylesheet">

    <!-- Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="form-validation.css" rel="stylesheet">

    <!-- Jquery -->
    <script src="assets/dist/js/jquery.min.js"></script>
</head>
<body>
<?php
if($ambiente != "live") { ?>
    <div class='alert-warning text-center fw-bold fixed-bottom'>
        <svg style="height: 15px; line-height: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c13.3 0 24 10.7 24 24V264c0 13.3-10.7 24-24 24s-24-10.7-24-24V152c0-13.3 10.7-24 24-24zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg>
        Ambiente de homologação! Não gera pagamentos reais.
        <svg style="height: 15px; line-height: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c13.3 0 24 10.7 24 24V264c0 13.3-10.7 24-24 24s-24-10.7-24-24V152c0-13.3 10.7-24 24-24zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg>
    </div>
<?php } ?>
<div class="container">

    <main>

        <div class="py-2 text-center">
            <img src="https://www.quatroventos.com.br/assets/logo-quatroventos.svg" alt="Quatro Ventos - Desenvolvimento de Sites" width="300" height="70">
        </div>


