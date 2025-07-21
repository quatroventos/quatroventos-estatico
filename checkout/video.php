<?php include('header.php'); ?>

<?php
//integração HS
//separa nome e sobrenome
$nome_completo = $_POST['nome'];
$nome_sobrenome = explode(' ', $nome_completo);
$nome = $nome_sobrenome[0];
$sobrenome = end($nome_sobrenome);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.hubapi.com/contacts/v1/contact/',
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
      "property": "email",
      "value": "'.$_POST['email'].'"
    },
    {
      "property": "firstname",
      "value": "'.$nome.'"
    },
    {
      "property": "lastname",
      "value": "'.$sobrenome.'"
    },
    {
      "property": "phone",
      "value": "'.$_POST['celular'].'"
    }
  ]
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer pat-na1-a615a075-7ae4-47c2-aa43-d78aee5ab239',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$responsearray = json_decode($response);

//echo "<pre>";
//print_r($responsearray);
//echo "</pre>";

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.hubapi.com/deals/v1/deal',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
  "associations": {
    "associatedVids": [
      '.$responsearray->vid.'
    ]
  },
  "properties": [
    {
      "value": "Assinatura CMO 2023 ('.$_POST['nome'].')",
      "name": "dealname"
    },
    {
      "value": "64899981",
      "name": "dealstage"
    },
    {
      "value": "default",
      "name": "pipeline"
    },
    {
      "value": "171792975",
      "name": "hubspot_owner_id"
    },
    {
      "value": '.strtotime("+1 week").',
      "name": "closedate"
    },
    {
      "value": "'.$course_price.'",
      "name": "amount"
    },
    {
      "value": "newbusiness",
      "name": "dealtype"
    }
  ]
}',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer pat-na1-a615a075-7ae4-47c2-aa43-d78aee5ab239',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;

$responsearray = json_decode($response);

//header('Location:payment.php');
?>

<!---->
<!--<a href="javascript:history.back(-1)" class="backlink"><svg style="color:white; fill: white; height: 20px; width: auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 288 480 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-370.7 0 73.4-73.4c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-128 128z"/></svg> Voltar</a>-->
<!---->
<!--    <form action="payment.php" method="post" novalidate>-->
<!---->
<!--        <input type="hidden" name="nome" value="--><?php //echo $_POST['nome'] ?><!--">-->
<!--        <input type="hidden" name="email" value="--><?php //echo $_POST['email'] ?><!--">-->
<!--        <input type="hidden" name="celular" value="--><?php //echo $_POST['celular']; ?><!-- ">-->
<!--        <input type="hidden" name="dealid" value="--><?php //echo $responsearray->dealId; ?><!--">-->
<!---->
<!---->
<!--        <div class="row g-5">-->
<!--            <div class="mx-auto col-11 col-md-8 col-lg-6">-->
<!--                <h1 class="mb-3 text-center">Assista ao vídeo e saiba tudo sobre... </h1>-->
<!---->
<!--                <div class="steps col-12 mb-5">-->
<!--                    <div class="step active"></div>-->
<!--                    <div class="step active"></div>-->
<!--                    <div class="step"></div>-->
<!--                    <div class="step"></div>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!---->
<!--        </div>-->
<!---->
<!--        <div class="video-container">-->
<!--            <iframe-->
<!--                    src="https://www.youtube.com/embed/fbYFS16FpQk"-->
<!--                    frameborder="0"-->
<!--                    allow="accelerometer; autoplay; encrypted-media; gyroscope;"-->
<!--                    allowfullscreen></iframe>-->
<!--        </div>-->
<!--        <div class="row ">-->
<!--            <div class="col text-center">-->
<!--                <button class="btn btn-primary btn-lg" type="submit">Continuar</button>-->
<!--            </div></div>-->
<!---->
<!--    </form>-->

<?php include('footer.php'); ?>
