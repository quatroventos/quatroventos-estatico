<?php
require 'vendor/autoload.php'; // caminho relacionado a SDK
include('config.php');

use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;

/*
* Este token será recebido em sua variável que representa os parâmetros do POST
* Ex.: $_POST['notification']
*/

$params = ['token' => $_POST['notification']];

try {
    $api = new Gerencianet($options);
    $chargeNotification = $api->getNotification($params, []);
    // Para identificar o status atual da sua transação você deverá contar o número de situações contidas no array,
    // pois a última posição guarda sempre o último status.
    // Veja na um modelo de respostas na seção "Exemplos de respostas" abaixo.

    // Veja abaixo como acessar o ID e a String referente ao último status da transação.

    // Conta o tamanho do array data (que armazena o resultado)
    $i = count($chargeNotification["data"]);
    // Pega o último Object chargeStatus
    $ultimoStatus = $chargeNotification["data"][$i-1];
    // Acessando o array Status
    $status = $ultimoStatus["status"];
    // Obtendo o ID da transação
    $charge_id = $ultimoStatus["identifiers"]["charge_id"];
    // Obtendo a String do status atual
    $statusAtual = $status["current"];

//    echo '<pre>';
//    print_r($chargeNotification);
//    echo '</pre>';
//     Com estas informações, você poderá consultar sua base de dados e atualizar o status da transação especifica, uma vez que você possui o "charge_id" e a String do STATUS

//    echo "O id da transação é: ".$charge_id." seu novo status é: ".$statusAtual;

    header("HTTP/1.1 200");
    //print_r($chargeNotification);
} catch (GerencianetException $e) {
//    print_r($e->code);
//    print_r($e->error);
//    print_r($e->errorDescription);
    header("HTTP/1.1 400");
} catch (Exception $e) {
//    print_r($e->getMessage());
    header("HTTP/1.1 401");
}

// Conexão com o banco de dados
$conn = mysqli_connect($host, $username, $password, $database_name);
if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}

// Query SQL para inserir os dados na tabela checkouts
$sql = "UPDATE checkouts SET status='".$statusAtual."' WHERE charge_id='".$charge_id."'";

// Execução da query SQL
if (mysqli_query($conn, $sql)) {
    echo "Status ".$statusAtual." salvos com sucesso na tabela checkouts!";
} else {
    echo "Erro ao salvar dados: " . mysqli_error($conn);
}

// Fechamento da conexão com o banco de dados
mysqli_close($conn);


//faz o enrol

// Query SQL para inserir os dados na tabela checkouts
// Conexão com o banco de dados
$conn = mysqli_connect($host, $username, $password, $database_name);
if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}
$sql = "SELECT * FROM checkouts WHERE charge_id='".$charge_id."'";
$checkoutdata = mysqli_fetch_assoc(mysqli_query($conn, $sql));

//muda status do lead de acordo com o retorno da operadora
if($statusAtual == 'paid') {
    $stageid = "64899980";//compra confirmada

    //cadastra usuário no lw

    //cria nome de usuario
    $nomes = explode(" ", $checkoutdata['nome']);
    $primeiroNome = $nomes[0];
    $ultimoNome = end($nomes);
    $nomeUsuario = strtolower($primeiroNome . $ultimoNome);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.makersacademy.com.br/admin/api/v2/users',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
      "email": "' . $checkoutdata['email'] . '",
      "username": "' . $nomeUsuario . '",
      "subscribed_for_marketing_emails": null,
      "is_admin": false,
      "signup_validation_rules": true,
      "send_registration_email": true,
      "fields": {
      }
    }',
        CURLOPT_HTTPHEADER => array(
            'Lw-Client: 640f958d453e4c28a0017ebe',
            'Authorization: Bearer rVaKVZpDxMrjDHkPV84aqDniZfwPb4ZneL12SImB',
            'Content-Type: application/json',
            'Cookie: slim_session=gHKH7wNtOOoBfWOAYHUzNlCrfWrAYqCewqyMMP3u'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $userdata = json_decode($response);

    //faz  enrol no curso
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.makersacademy.com.br/admin/api/v2/users/' . $userdata->id . '/enrollment?Lw-Client=640f958d453e4c28a0017ebe',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
      "productId":"'.$course_id.'",
      "productType": "course",
      "justification": "Comprado via Checkout CMO 2023",
      "price": '.$course_price.',
      "send_enrollment_email": true
    }',
        CURLOPT_HTTPHEADER => array(
            'Lw-Client: 640f958d453e4c28a0017ebe',
            'Authorization: Bearer rVaKVZpDxMrjDHkPV84aqDniZfwPb4ZneL12SImB',
            'Content-Type: application/json',
            'Cookie: slim_session=gHKH7wNtOOoBfWOAYHUzNlCrfWrAYqCewqyMMP3u'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
}else{
    $stageid = "64022164"; //compra não confirmada
}


//muda status no hubpsot
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.hubapi.com/deals/v1/deal/'.$checkoutdata['dealid'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_POSTFIELDS => '{
  "properties": [
   {
     "value": '.$stageid.',
      "name": "dealstage"
    }
  ]
}
',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer pat-na1-a615a075-7ae4-47c2-aa43-d78aee5ab239',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);
//echo $response;
curl_close($curl);
