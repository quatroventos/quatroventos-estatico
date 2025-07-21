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
    <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200|Open+Sans+Condensed:700" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous"> -->
    <!-- CSS Files -->
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./assets/css/now-ui-kit.css?v=1.3.1" rel="stylesheet" />
    <link href="./assets/demo/demo.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" /> -->
<!-- FONTE -->
<link rel="stylesheet" href="./assets/css/fonts.css">
    <!-- Mask plugin for jquery -->


</head>

<body class="signup-page sidebar-collapse">

<?php include('header.php');?>

<!-- End Navbar -->
<div class="page-header header-filter" filter-color="black">
    <div class="page-header-image" style="background-image:url(./assets/img/bg18.jpg)"></div>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-4 ml-auto mr-auto signup">
                    <div id="startup"><?php include('inc-mtnc-card-startup.php'); ?></div>
                    <div id="standard"><?php include('inc-mtnc-card-standard.php'); ?></div>
                    <div id="ecommerce"><?php include('inc-mtnc-card-ecommerce.php'); ?></div>
                </div>
                <div class="col-md-6 mr-auto">
                    <div class="card card-signup">
                        <div class="card-body">
                            <h4 class="card-title text-center">Vamos começar?</h4>

                            <form class="form" method="post" action="checkout/payment.php">

                                <input type="hidden" id="precoPost" name="preco" value="">
                                <input type="hidden" id="planID" name="id_plano" value="">


                                <div class="text-center">
                                    <h5 class="card-description">Escolha seu plano</h5>
                                </div>

                                <div class="form-row">
                                    <select id="plano" name="plano" class="selectpicker" data-size="15" data-style="btn btn-default btn-outline-default btn-round" title="Single Select">
                                        <option <?php echo ($_GET['plan'] == 'startup' ? 'selected' : ''); ?> value="Plano Startup anual" data-desc="R$ 1.188,00 / ano <small>(equivale a R$ 99,00 / mês)</small>" data-preco="118800" data-planid="7329">Plano Startup anual (equivale a R$ 99,00 / mês)</option>
                                        <option <?php echo ($_GET['plan'] == 'standard' ? 'selected' : ''); ?> value="Plano Standard anual" data-desc="R$ 2.587,00 / ano <small>(equivale a R$ 199,00 / mês)</small>" data-preco="258700" data-planid="7329">Plano Standard anual (equivale a R$ 199,00 / mês)</option>
                                        <option <?php echo ($_GET['plan'] == 'ecommerce' ? 'selected' : ''); ?> value="Plano E-commerce anual" data-desc="R$ 4.308,00 / ano <small>(equivale a R$ 359,00 / mês)</small>" data-preco="430800" data-planid="7329">Plano E-commerce anual (equivale a R$ 359,00 / mês)</option>
                                        <option disabled>-</option>
                                        <option value="Plano Startup mensal" data-desc="R$ 120,00 / mês" data-preco="12000" data-planid="7329">Plano Startup mensal (R$ 120,00 / mês)</option>
                                        <option value="Plano Standard mensal" data-desc="R$ 250,00 / mês" data-preco="25000" data-planid="7329">Plano Standard mensal (R$ 250,00 / mês)</option>
                                        <option value="Plano E-commerce mensal" data-desc="R$ 450,00 / mês" data-preco="45000" data-planid="7329">Plano E-commerce mensaln (R$ 450,00 / mês)</option>
                                    </select>
                                </div>

                                <div class="text-center">
                                    <h6 class="card-description"><strong>Total do pedido: <span id="preco" style="color:black;"></span></strong></h6>
                                    <hr>
                                </div>
<!--
                                <div class="text-center">
                                    <h5 class="card-description">Fale um pouco sobre o seu site</h5>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="url" class="form-control" placeholder="URL">
                                </div>
                                <div class="form-group">
                                    <select class="selectpicker" name="plataforma" data-size="15" data-style="btn btn-default btn-outline-default btn-round" title="Single Select">
                                        <option disabled selected>Plataforma</option>
                                        <option value="Wordpress / Woocommerce">Wordpress / Woocommerce</option>
                                        <option value="Joomla">Joomla</option>
                                        <option value="Drupal">Drupal</option>
                                        <option value="Blogger">Blogger</option>
                                        <option value="Shopify">Shopify</option>
                                        <option value="Opencart">Opencart</option>
                                        <option value="Magento">Magento</option>
                                        <option value="Vtex">Vtex</option>
                                        <option value="Nuvemshop">Nuvemshop</option>
                                        <option value="Linx">Linx</option>
                                        <option value="Betalabs">Betalabs</option>
                                        <option value="Loja Integrada">Loja integrada</option>
                                        <option value="Prestashop">Prestashop</option>
                                        <option value="Wix">Wix</option>
                                        <option value="Outra">Outra / Não sei</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <textarea  name="obs" class="form-control" placeholder="Observações"></textarea>
                                </div>
-->

                                <div class="text-center">
                                    <h5 class="card-description">Vamos nos conhecer?</h5>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <input type="text" name="nome" class="form-control" placeholder="Nome" autocomplete="nome">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <input type="text" name="sobrenome" class="form-control" placeholder="Sobrenome" autocomplete="sobrenome">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <input type="email" name="email" class="form-control" placeholder="E-mail" autocomplete="email">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <input type="text"  name="telefone" class="form-control phone_with_ddd" placeholder="Telefone" autocomplete="telefone">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <h5 class="card-description">Agora alguns detalhes para nosso financeiro</h5>
                                </div>

                                <!-- juridica ou fisica? -->

                                <div class="form-group">
                                <div class="form-check form-check-radio">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="pessoa" id="radiojuridica" value="juridica" checked>
                                        <span class="form-check-sign"></span>
                                        Pessoa Jurídica
                                    </label>
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="pessoa" id="radiofisica" value="fisica">
                                        <span class="form-check-sign"></span>
                                        Pessoa Física
                                    </label>
                                </div>
                                </div>

                                <!-- juridica ou fisica? -->

                                <div class="form-group">
                                    <input type="text" name="razsoc" class="form-control juridica" placeholder="Razão social">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="cnpj" class="form-control juridica cnpj" placeholder="CNPJ">
                                </div>

                                <div class="form-group">
                                    <input type="text" name="cpf" class="form-control fisica cpf" placeholder="CPF">
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4 col-sm-6">
                                        <input type="text" name="cep" id="cep" class="form-control cep" placeholder="CEP">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-6">
                                        <input type="text" name="rua" id="logradouro" class="form-control" placeholder="Rua">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-6">
                                        <input type="text" name="numero" class="form-control" placeholder="Número">
                                    </div>
                                    <div class="form-group col-md-2 col-sm-6">
                                        <input type="text" name="complemento" class="form-control" placeholder="Complemento">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <input type="text" name="cidade" class="form-control" id="cidade" placeholder="Cidade">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <input type="text" name="bairro" class="form-control" id="bairro" placeholder="Bairro">
                                    </div>
                                    <div class="form-group col-md-2 col-sm-6">
                                        <input type="text" name="estado" class="form-control" id="uf" placeholder="Estado">
                                    </div>
                                </div>

                                <hr>
                                <div class="text-center">
                                    <h5 class="card-description">Qual será a forma de pagamento?</h5>
                                </div>
                                <!-- tabs -->

                                <ul class="nav nav-pills nav-pills-primary">
                                    <li class="nav-item" style="margin: 0 auto;"><a class="nav-link active" href="#pill1" data-toggle="tab"><i class="now-ui-icons shopping_credit-card"></i> Cartão de crédito</a></li>
                                    <li class="nav-item" style="margin: 0 auto;"><a class="nav-link" href="#pill2" data-toggle="tab"><i class="now-ui-icons files_paper"></i> Boleto bancário</a></li>
                                    <!--<li class="nav-item"><a class="nav-link" href="#pill2" data-toggle="tab">Pagar com PIX</a></li>-->
                                </ul>

                                <br>

                                <div class="tab-content tab-space creditcard">
                                    <div class="tab-pane active" id="pill1">

                                        <input type="hidden" id="token" name="token" value="">
                                        <input type="hidden" id="brand" name="brand" value="">

                                        <div class="input-group">
                                            <div class="creditcardbrand" id="visa" style="color:black;"><img src="checkout/cards/visa.png" title="visa" alt="visa"></div>
                                            <div class="creditcardbrand" id="mastercard" style="color:black;"><img src="checkout/cards/master.png" title="master" alt="master"></div>
                                            <div class="creditcardbrand" id="amex" style="color:black;"><img src="checkout/cards/amex.png" title="amex" alt="amex"></div>
                                            <div class="creditcardbrand" id="elo" style="color:black;"><img src="checkout/cards/elo.png" title="elo" alt="elo"></div>
                                            <div class="creditcardbrand" id="hipercard" style="color:black;"><img src="checkout/cards/hiper.png" title="hiper" alt="hiper"></div>
                                        </div>

                                        <br>

                                        <div class="input-group">
                                            <input type="text" value="Gorbadoc Oldbuck" name="nome_titular" class="form-control" placeholder="Nome do titular">
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <input type="text" value="94271564656" name="cpf_titular" class="form-control cpf" placeholder="CPF do titular">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <input type="text" value="15/01/1977" name="aniversario_titular" class="form-control date" placeholder="Data de nascimento do titular">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-9 col-sm-12">
                                                <p>4012001038443335</p>
                                                <input type="text" value="" id="number" name="number" class="form-control" placeholder="Número do cartão">
                                            </div>
                                            <div class="form-group col-md-3 col-sm-12">
                                                <input type="text" value="123" id="cvv" name="cvv" class="form-control" placeholder="CVV">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <input type="text" value="05" id="exp_month" name="exp_month" class="form-control" placeholder="Mês de validade">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <input type="text" value="2021" id="exp_year" name="exp_year" class="form-control" placeholder="Ano de validade">
                                            </div>
                                        </div>

                                        <div class="input-group">
                                            <select class="form-control" name="installments" id="installments"  disabled data-size="15" data-style="btn btn-default btn-outline-default btn-round" title="Single Select">
                                                <option disabled selected>Parcelamento</option>
                                            </select>
                                        </div>

                                        <div class="form-check text-lefts">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="aceite" type="checkbox">
                                                <span class="form-check-sign"></span>
                                                Concordo com os <a href="#" data-toggle="modal" data-target="#contrato">termos para adesão <i style="font-size:8px; vertical-align:4px;" class="fas fa-external-link-alt"></i></a> do serviço.
                                            </label>
                                        </div>

                                        <div class="card-footer text-center">
                                            <a id="payCardButton" class="btn btn-primary btn-round btn-lg">Pagar com cartão de crédito</a>
                                        </div>

                                    </div>
                                    <div class="tab-pane" id="pill2">

                                        <div class="form-check text-lefts">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="aceite" type="checkbox">
                                                <span class="form-check-sign"></span>
                                                Concordo com os <a href="#" data-toggle="modal" data-target="#contrato">termos para adesão <i style="font-size:8px; vertical-align:4px;" class="fas fa-external-link-alt"></i></a> do serviço.
                                            </label>
                                        </div>
                                        <div class="card-footer text-center">
                                            <button type="submit" class="btn btn-primary btn-round btn-lg">Emitir boleto bancário</button>
                                        </div>

                                    </div>
                                </div>

                                <!-- tabs -->

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="contrato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color:black;">
                    <?php include('contrato.php'); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="./assets/js/jquery.mask.min.js"></script>
    <script src="checkout/checkout.js"></script>

    <?php include('footer.php'); ?>