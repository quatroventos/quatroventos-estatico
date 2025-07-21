<?php

/*
 *
 * array(18) {
  ["preco"]=>
  string(6) "118800"
  ["id_plano"]=>
  string(4) "7329"
  ["plano"]=>
  string(19) "Plano Startup anual"
  ["nome"]=>
  string(7) "Gabriel"
  ["sobrenome"]=>
  string(6) "Roloff"
  ["email"]=>
  string(27) "gabriel@quatroventos.com.br"
  ["telefone"]=>
  string(15) "(41) 98864-5007"
  ["pessoa"]=>
  string(8) "juridica"
  ["razsoc"]=>
  string(13) "Quatro VEntos"
  ["cnpj"]=>
  string(18) "12.390.809/8099-90"
  ["cpf"]=>
  string(0) ""
  ["cep"]=>
  string(9) "81090-190"
  ["rua"]=>
  string(7) "Itajuba"
  ["numero"]=>
  string(3) "480"
  ["complemento"]=>
  string(5) "ap303"
  ["cidade"]=>
  string(8) "curitiba"
  ["bairro"]=>
  string(6) "portao"
  ["estado"]=>
  string(2) "pr"
}
 */


$preco=$_POST['preco'];
$plano=$_POST['Plano Startup anual'];

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.hubapi.com/deals/v1/deal?hapikey=1ef91bbe-6611-4dd9-9a15-1a3d14d04a04',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
  "properties": [
    {
      "value": "Nova assinatura ({$plano})",
      "name": "dealname"
    },
    {
      "value": "appointmentscheduled",
      "name": "dealstage"
    },
    {
      "value": "default",
      "name": "pipeline"
    },
    {
      "value": 1409443200000,
      "name": "closedate"
    },
    {
      "value": "{$preco}",
      "name": "amount"
    },
    {
      "value": "newbusiness",
      "name": "dealtype"
    }
  ]
}',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer 1ef91bbe-6611-4dd9-9a15-1a3d14d04a04',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
