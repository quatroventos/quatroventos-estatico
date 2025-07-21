<?php
//opções gerais
$total = $_GET['p']; //preço do serviço
$services = $_GET['s'];
$description = $_GET['d'];
$dominio = $_SERVER['HTTP_HOST'];
$ambiente = 'live'; // sandbox ou live

////Gerencianet
//if ($ambiente == 'live') {
//
//    //producao
//    $options = [
//        "client_id" => 'Client_Id_8942ae412365c4c6ee176caf0e980d9cec7fe9d7',
//        "client_secret" => 'Client_Secret_c779e907e8e96bdd74272ef82bf50e0348113923',
//        "certificate" => realpath( "producao-442485-cmo2023.p12"), // Absolute path to the certificate in .pem or .p12 format
//        "sandbox" => false,
//        "debug" => false,
//        "timeout" => 30
//    ];
////    $notification_url = "https://checkout.themakers.com.br/approve.php";
//
//}else{
//
//    $options = [
//        "client_id" => 'Client_Id_180230b3358eb65b71d5654b2bcd0235bf222a36',
//        "client_secret" => 'Client_Secret_3c4c64dbed530e1e319976345bbc09f17ea03c33',
//        "certificate" => realpath("homologacao-442485-cmo2023.p12"), // Absolute path to the certificate in .pem or .p12 format
//        "sandbox" => true,
//        "debug" => false,
//        "timeout" => 30
//    ];

//    $notification_url = "http://dev.quatroventos.com.br/cmo/approve.php";

//}

//
//
////Mysql
//if ($dominio == 'checkout.themakers.com.br') {
//    // Credenciais do banco de dados dev
//    $host = "localhost"; // endereço do servidor
//    $username = "cmo_checkout"; // nome de usuário
//    $password = "wovCorcyjx"; // senha
//    $database_name = "cmo_checkout"; // nome do banco de dados
//}else if ($dominio == 'dev.quatroventos.com.br') {
//    // Credenciais do banco de dados local
//    $host = "127.0.0.1"; // endereço do servidor
//    $username = "cmo"; // nome de usuário
//    $password = "cmo2023"; // senha
//    $database_name = "makers_cmo"; // nome do banco de dados
//}else{
//    // Credenciais do banco de dados local
//    $host = "127.0.0.1"; // endereço do servidor
//    $username = "root"; // nome de usuário
//    $password = ""; // senha
//    $database_name = "makers_cmo"; // nome do banco de dados
//}

?>
