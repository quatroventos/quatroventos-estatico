<?php
//session_start();
//if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//    http_response_code(403);
//    header('location:error.php');
//}

include('config.php');

//This example was built using pagarme-core-api-php SDK.
//For more information, please refer to https://github.com/pagarme/pagarme-core-api-php
require_once  "vendor/autoload.php" ;

$expiration_date = $_POST['expiration']; // data no formato MM/YY
$expiration_parts = explode('/', $expiration_date); // divide a string em duas partes, usando a barra como delimitador

$exp_month = $expiration_parts[0]; // a primeira parte é o mês
$exp_year = $expiration_parts[1]; // a segunda parte é o ano

$basicAuthUserName = 'sk_BoXjqLziwkC1amL1'; // The username to use with basic authentication
$basicAuthPassword = ''; // The password to use with basic authentication

$apiClient = new PagarmeCoreApiLib\PagarmeCoreApiClient($basicAuthUserName, $basicAuthPassword);

$orderController = $apiClient->getOrders();


$customer = new PagarmeCoreApiLib\Models\CreateCustomerRequest();
$customer->name = $_POST['nome'];

$creditCard = new PagarmeCoreApiLib\Models\CreateCreditCardPaymentRequest();
$creditCard->capture = true;
$creditCard->installments = $_POST['installments'];
$creditCard->card = new PagarmeCoreApiLib\Models\CreateCardRequest();
$creditCard->card->number = $_POST['number'];;
$creditCard->card->holderName = $_POST['holder_name'];;
$creditCard->card->expMonth = $exp_month;
$creditCard->card->expYear = $exp_year;
$creditCard->card->cvv = $_POST['cvv'];

$request = new PagarmeCoreApiLib\Models\CreateOrderRequest();

$request->items = [new PagarmeCoreApiLib\Models\CreateOrderItemRequest()];
$request->items[0]->description = $_POST['description'];
$request->items[0]->quantity = 1;
$request->items[0]->amount = $_POST['total']; // this value should be in cents

$request->payments = [new PagarmeCoreApiLib\Models\CreatePaymentRequest()];
$request->payments[0]->paymentMethod = "credit_card";
$request->payments[0]->creditCard = $creditCard;
$request->customer = $customer;

//echo json_encode($request);

$result = $orderController->createOrder($request);

//echo json_encode($result, JSON_PRETTY_PRINT);

header('location:thankyou.php');
?>