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
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        Quatro Ventos - Especialistas em sites
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200|Open+Sans+Condensed:700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!-- CSS Files -->
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./assets/css/now-ui-kit.css?v=1.3.1" rel="stylesheet" />
    <link href="./assets/demo/demo.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
</head>

<body class="signup-page sidebar-collapse">

<?php include('header.php');?>
<!-- End Navbar -->
<div class="page-header header-filter" filter-color="black">
    <div class="page-header-image" style="background-image:url(../assets/img/bg18.jpg)"></div>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-4 ml-auto mr-auto">
                    <div class="info info-horizontal">
                        <div class="icon icon-primary">
                            <i class="now-ui-icons emoticons_satisfied"></i>
                        </div>
                        <div class="description">
                            <h5 class="info-title">Obrigado!</h5>
                            <p class="description">
                                Seu pedido foi realizado! Assim que recebermos a confirmação do seu pagamento, entraremos em contato para iniciar seu atendimento.
                                <a href="<?php echo $_GET['link']; ?>" class="btn btn-primary btn-round">Imprimir boleto</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $( document ).ready(function(){
            var preco = $('#plano').val();
            $('#preco').html(preco);
        })

        $('#plano').on('change', function(){
            var preco = $(this).val();
            $('#preco').html(preco);
        })

        $("#cep").keyup(function(){
            //Início do Comando AJAX
            $.ajax({
                //O campo URL diz o caminho de onde virá os dados
                //É importante concatenar o valor digitado no CEP
                url: 'https://viacep.com.br/ws/'+$(this).val()+'/json/unicode/',
                //Aqui você deve preencher o tipo de dados que será lido,
                //no caso, estamos lendo JSON.
                dataType: 'json',
                //SUCESS é referente a função que será executada caso
                //ele consiga ler a fonte de dados com sucesso.
                //O parâmetro dentro da função se refere ao nome da variável
                //que você vai dar para ler esse objeto.
                success: function(resposta){
                    //Agora basta definir os valores que você deseja preencher
                    //automaticamente nos campos acima.
                    $("#logradouro").val(resposta.logradouro);
                    $("#complemento").val(resposta.complemento);
                    $("#bairro").val(resposta.bairro);
                    $("#cidade").val(resposta.localidade);
                    $("#uf").val(resposta.uf);
                    //Vamos incluir para que o Número seja focado automaticamente
                    //melhorando a experiência do usuário
                    $("#numero").focus();
                }
            });
        });

        $('#payCardButton').on('click', function(){

            var s = document.createElement('script');
            s.type = 'text/javascript';
            var v = parseInt(Math.random() * 1000000);
            s.src = 'https://sandbox.gerencianet.com.br/v1/cdn/f4f99363885c8d76fc7559e393a634d7/' + v;
            s.async = false;
            s.id = 'f4f99363885c8d76fc7559e393a634d7';
            if (!document.getElementById('f4f99363885c8d76fc7559e393a634d7')) {
                document.getElementsByTagName('head')[0].appendChild(s);
            }
            ;$gn = {
                validForm: true, processed: false, done: {}, ready: function (fn) {
                    $gn.done = fn;
                }
            };

            $gn.ready(function(checkout) {

                var callback = function(error, response) {
                    if(error) {
                        // Trata o erro ocorrido
                        console.error(error);
                    } else {
                        // Trata a resposta
                        console.log(response);
                    }
                };

                checkout.getPaymentToken({
                    brand: 'visa', // bandeira do cartão
                    number: '4012001038443335', // número do cartão
                    cvv: '123', // código de segurança
                    expiration_month: '05', // mês de vencimento
                    expiration_year: '2021' // ano de vencimento
                }, callback);

                /*var brand = $('#brand').val()
                var number = $('#number').val()
                var cvv = $('#cvv').val()
                var exp_month = $('#exp_month').val()
                var exp_year = $('#exp_year').val()

                checkout.getPaymentToken({
                    brand: brand, // bandeira do cartão
                    number: number, // número do cartão
                    cvv: cvv, // código de segurança
                    expiration_month: exp_month, // mês de vencimento
                    expiration_year: exp_year // ano de vencimento
                }, callback);
*/
            });
        })

    </script>

    <?php include('footer.php'); ?>