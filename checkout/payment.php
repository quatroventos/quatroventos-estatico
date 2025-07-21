<?php
session_start();
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
include('header.php');
include('config.php');
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = preg_replace('/[^0-9]/', '', $_POST['celular']);
//
////integração HS
////separa nome e sobrenome
//$nome_completo = $_POST['nome'];
//$nome_sobrenome = explode(' ', $nome_completo);
//$nome = $nome_sobrenome[0];
//$sobrenome = end($nome_sobrenome);
//
//$curl = curl_init();
//
//curl_setopt_array($curl, array(
//    CURLOPT_URL => 'https://api.hubapi.com/contacts/v1/contact/',
//    CURLOPT_RETURNTRANSFER => true,
//    CURLOPT_ENCODING => '',
//    CURLOPT_MAXREDIRS => 10,
//    CURLOPT_TIMEOUT => 0,
//    CURLOPT_FOLLOWLOCATION => true,
//    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//    CURLOPT_CUSTOMREQUEST => 'POST',
//    CURLOPT_POSTFIELDS => '{
//  "properties": [
//    {
//      "property": "email",
//      "value": "' . $_POST['email'] . '"
//    },
//    {
//      "property": "firstname",
//      "value": "' . $nome . '"
//    },
//    {
//      "property": "lastname",
//      "value": "' . $sobrenome . '"
//    },
//    {
//      "property": "phone",
//      "value": "' . $_POST['celular'] . '"
//    }
//  ]
//}',
//    CURLOPT_HTTPHEADER => array(
//        'Authorization: Bearer pat-na1-a615a075-7ae4-47c2-aa43-d78aee5ab239',
//        'Content-Type: application/json'
//    ),
//));
//
//$response = curl_exec($curl);
//
//curl_close($curl);
//$responsearray = json_decode($response);
//
////echo "<pre>";
////print_r($responsearray);
////echo "</pre>";
//
//$curl = curl_init();
//
//curl_setopt_array($curl, array(
//    CURLOPT_URL => 'https://api.hubapi.com/deals/v1/deal',
//    CURLOPT_RETURNTRANSFER => true,
//    CURLOPT_ENCODING => '',
//    CURLOPT_MAXREDIRS => 10,
//    CURLOPT_TIMEOUT => 0,
//    CURLOPT_FOLLOWLOCATION => true,
//    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//    CURLOPT_CUSTOMREQUEST => 'POST',
//    CURLOPT_POSTFIELDS => '{
//  "associations": {
//    "associatedVids": [
//      ' . $responsearray->vid . '
//    ]
//  },
//  "properties": [
//    {
//      "value": "Exponential Marketing 2023 (' . $_POST['nome'] . ' - ' .$_POST['celular'] .')",
//      "name": "dealname"
//    },
//    {
//      "value": "64899981",
//      "name": "dealstage"
//    },
//    {
//      "value": "default",
//      "name": "pipeline"
//    },
//    {
//      "value": "171792975",
//      "name": "hubspot_owner_id"
//    },
//    {
//      "value": ' . strtotime("+1 week") . ',
//      "name": "closedate"
//    },
//    {
//      "value": "' . $course_price . '",
//      "name": "amount"
//    },
//    {
//      "value": "newbusiness",
//      "name": "dealtype"
//    }
//  ]
//}',
//    CURLOPT_HTTPHEADER => array(
//        'Authorization: Bearer pat-na1-a615a075-7ae4-47c2-aa43-d78aee5ab239',
//        'Content-Type: application/json'
//    ),
//));
//

//
////salva lead no banco de dados
//// Conexão com o banco de dados
//$conn = mysqli_connect($host, $username, $password, $database_name);
//if (!$conn) {
//    die("Conexão falhou: " . mysqli_connect_error());
//}
//
//// Query SQL para inserir os dados na tabela checkouts
//$sql = "INSERT INTO checkouts ( nome, email, telefone, vid) VALUES ('$nome', '$email', '$telefone', '$responsearray->vid')";
//
//// Execução da query SQL
//mysqli_query($conn, $sql);
//
//// Fechamento da conexão com o banco de dados
//mysqli_close($conn);
//
//
//$response = curl_exec($curl);
//curl_close($curl);
//$responsearray = json_decode($response);
//
//
//    function calcularParcelas($valorTotal) {
//        $jurosMensal = 0.0199; // 1,99% ao mês
//        $numParcelas = 10;
//        $jurosCompostos = pow(1 + $jurosMensal, $numParcelas);
//        $parcela = $valorTotal * $jurosMensal * $jurosCompostos / ($jurosCompostos - 1);
//        return $parcela;
//    }
//
//?>

<?php if($ambiente != 'live'){ ?>
    <div class="testdata" style="position:fixed; top:50%; background: white; width: 300px; height: 350px; margin-top: -160px; left:2%; border: 1px dashed magenta; color: magenta; padding:20px; border-radius:20px;">
        <strong>Dados para teste</strong><hr>
        "document": 03154435026<br>
        "holder_name": Tony Stark<br>
        "number": 4000000000000010<br>
        "exp_month": 1<br>
        "exp_year": 30<br>
        "cvv": 123,<br>
        <hr>
        <small>*essas informações desaparecerão quando entrar em produção.</small>
    </div>
<?php } ?>

<a href="javascript:history.back(-1)" class="backlink"><svg style="color:white; fill: white; height: 20px; width: auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 288 480 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-370.7 0 73.4-73.4c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-128 128z"/></svg> Voltar</a>

<div class="row">
    <div class="mx-auto col-11 col-md-8 col-lg-6">
        <h1 class="mb-3 text-center">Você está quase lá!</h1>
        <p class="mb-3 text-center">Agora, preencha os dados abaixo para realizar o pagamento do curso.</p>

        <div class="steps col-12 mb-5">
            <div class="step active"></div>
            <div class="step active"></div>
            <div class="step"></div>
<!--            <div class="step"></div>-->
        </div>

        <div class="">
            <div class="row g-3">
                <form action="gateway.php" method="post" class="needs-validation" id='payment' novalidate>

                    <div class="col-12">
                        <label for="holder_name" class="form-label">Nome no cartão*</label>
<!--                        <input type="text"  value="Gorbadoc Oldbuck" name="holder_name" class="form-control" placeholder="Nome do titular">-->
                        <input type="text"  value="" name="holder_name" id="holder_name" class="form-control" placeholder="Nome do titular" required>
                        <div class="invalid-feedback invalid-name">
                            Obrigatório informar o nome completo
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="holder_doc" class="form-label">CPF do titular*</label>
<!--                        <input type="text" value="94271564656" name="holder_doc" class="form-control cpf" placeholder="CPF do titular">-->
                        <input type="text" value="" name="holder_doc" class="form-control cpf" placeholder="CPF do titular" required>
                        <div class="invalid-feedback">
                            Obrigatório informar o CPF
                        </div>
                    </div>

                    <div class="col-12" style="position:relative;">
                        <label for="number" class="form-label">Número do cartão*</label>
<!--                        <input type="text" value="4012001038443335" id="number" name="number" class="form-control" placeholder="Número do cartão">-->
                        <input type="text" value="" id="number" name="number" class="form-control creditcard" placeholder="Número do cartão" required>

                        <div class="creditcardbrand" id="visa" style="color:black;"><img src="assets/cards/visa.png" title="visa" alt="visa"></div>
                        <div class="creditcardbrand" id="mastercard" style="color:black;"><img src="assets/cards/master.png" title="master" alt="master"></div>
                        <div class="creditcardbrand" id="amex" style="color:black;"><img src="assets/cards/amex.png" title="amex" alt="amex"></div>
                        <div class="creditcardbrand" id="elo" style="color:black;"><img src="assets/cards/elo.png" title="elo" alt="elo"></div>
                        <div class="creditcardbrand" id="hipercard" style="color:black;"><img src="assets/cards/hiper.png" title="hiper" alt="hiper"></div>

                        <div class="invalid-feedback invalid-card">
                            Verifique o número do cartão
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="expiration" class="form-label">Vencimento*</label>
<!--                            <input type="text" value="05/2024" id="expiration" name="expiration" class="form-control" placeholder="MM/AAAA">-->
                            <input type="text" value="" id="expiration" name="expiration" class="form-control carddate" placeholder="MM/AAAA" required>

                            <div class="invalid-feedback">
                                Obrigatório informar o vencimento
                            </div>
                        </div>

                        <div class="col">
                            <label for="cvv" class="form-label">CVV*</label>
<!--                            <input type="text" value="123" id="cvv" name="cvv" class="form-control" placeholder="CVV">-->
                            <input type="text" value="" id="cvv" name="cvv" class="form-control cvv" placeholder="CVV" required>
                            <div class="invalid-feedback">
                                Obrigatório informar o CVV
                            </div>
                        </div>
                    </div>

                    <div class="col-12 parcelamento" style="">
                        <label for="installments" class="form-label">Parcelamento*</label>
                        <select id="installments" name="installments" style="
                                height: 46px;
                                background: #545454;
                                border: none;
                                color: rgba(255, 255, 255, 0.64)" class="form-control">
                            <option>1 parcela</option>
                            <option>2 parcelas de <?php echo number_format(($total/2) / 100, 2, ',', '.'); // converte para reais ?></option>
                        </select>
                        <div class="invalid-feedback">
                            Obrigatório informar o número de parcelas
                        </div>
                    </div>

                    <div class="col-12" style="margin-top: 30px;">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="save-info" required>
                            <label class="form-check-label" for="save-info">Concordo com a <a href="https://themakers.com.br/politica-de-privacidade/" target="_blank">Política de Privacidade</a></label>
                        </div>
                    </div>

                    <button type="submit" id="submit" class="w-100 btn btn-primary btn-lg">Confirmar pagamento</button>


                    <input type="hidden" id="token" name="token" value="">
                    <input type="hidden" id="brand" name="brand" value="visa">
                    <input type="hidden" name="nome" value="<?php echo $_POST['nome'] ?>">
                    <input type="hidden" name="email" value="<?php echo $_POST['email'] ?>">
                    <input type="hidden" name="celular" value="<?php echo $_POST['celular'] ?>">
<!--                    <input type="hidden" name="dealid" value="--><?php //echo $_POST['dealid']; ?><!--">-->
<!--                    <input type="hidden" name="dealid" value="--><?php //echo $responsearray->dealId; ?><!--">-->
                    <input type="hidden" id="priceinput" name="price" value="<?php echo number_format($course_price, 2); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="cardToken" id="cardToken" value="">

                </form>
            </div>

        </div>
    </div>

</div>

    <!--// token de pagamento Gerencianet-->

    <!-- Homologação -->
    <?php if ($ambiente == 'live') { ?>
        <!-- Produção -->
        <script type='text/javascript'>var s=document.createElement('script');s.type='text/javascript';var v=parseInt(Math.random()*1000000);s.src='https://api.gerencianet.com.br/v1/cdn/c3e2cdb0478a63f1843e0bb4c398327c/'+v;s.async=false;s.id='c3e2cdb0478a63f1843e0bb4c398327c';if(!document.getElementById('c3e2cdb0478a63f1843e0bb4c398327c')){document.getElementsByTagName('head')[0].appendChild(s);};$gn={validForm:true,processed:false,done:{},ready:function(fn){$gn.done=fn;}};</script>
    <?php }else{ ?>
        <script type='text/javascript'>var s=document.createElement('script');s.type='text/javascript';var v=parseInt(Math.random()*1000000);s.src='https://sandbox.gerencianet.com.br/v1/cdn/c3e2cdb0478a63f1843e0bb4c398327c/'+v;s.async=false;s.id='c3e2cdb0478a63f1843e0bb4c398327c';if(!document.getElementById('c3e2cdb0478a63f1843e0bb4c398327c')){document.getElementsByTagName('head')[0].appendChild(s);};$gn={validForm:true,processed:false,done:{},ready:function(fn){$gn.done=fn;}};</script>
    <?php } ?>


<script>
    $(document).ready(function() {

        //onkeyup remove as mensagens de erro
        $('#holder_name').keyup(function (){
            $('.invalid-name').hide();
        });

    });

    $('#coupon_link').on('click', function(){
        $('#coupon-fields').slideToggle('fast');
    })

        //carrega variaveis
        $(document ).ready(function() {
            price = $('#priceinput').val();
        });


        //checa bandeira do cartão

        $("#number").on('change blur keyup', function () {
            var cardnumber = $(this).val();
            var cardnumber = cardnumber.replace(/[^0-9]+/g, '');
            var cards = {
                visa: /^4[0-9]{12}(?:[0-9]{3})/,
                mastercard: /^5[1-5][0-9]{14}/,
                diners: /^3(?:0[0-5]|[68][0-9])[0-9]{11}/,
                amex: /^3[47][0-9]{13}/,
                discover: /^6(?:011|5[0-9]{2})[0-9]{12}/,
                hipercard: /^(606282\d{10}(\d{3})?)|(3841\d{15})/,
                elo: /^((((636368)|(438935)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})/,
                jcb: /^(?:2131|1800|35\d{3})\d{11}/,
                aura: /^(5078\d{2})(\d{2})(\d{11})$/
            };
            $(".creditcardbrand").hide();
            for (var flag in cards) {
                if (cards[flag].test(cardnumber)) {
                    $('#brand').val(flag);
                    $('#' + flag).show();
                }
            }
        })

</script>




    <?php include('footer.php'); ?>
