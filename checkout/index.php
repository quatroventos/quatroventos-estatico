<?php include('header.php') ?>

    <div class="row">
      <div class="mx-auto col-11 col-md-11 col-lg-11">
        <h1 class="mb-3 text-center">Preencha os dados abaixo para efetuar o pagamento:</h1>
          <hr>
      </div>
    </div>

    <div class="row">

        <div class="mx-auto col-11 col-md-5 col-lg-5 mt-4">

            <div class="card">
                <div class="card-header">
                    Serviços:
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0"><?php echo ucfirst($services); ?></h6>
                                <small class="text-muted"><?php echo $description; ?></small>
                            </div>
                            <span class="text-muted"><?php echo number_format($total / 100, 2, ',', '.'); // converte para reais ?></span>
                        </li>

                    </ul>
                </div>
                <div class="card-footer">
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (BRL)</span>
                            <strong>R$ <?php echo number_format($total / 100, 2, ',', '.'); // converte para reais ?></strong>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="mx-auto col-11 col-md-5 col-lg-5">

            <form action="gateway.php" method="post" class="needs-validation" id="leaddata" novalidate>

                <p></p>
                <h5>Seus dados:</h5>
                <hr>
                <div class="row g-1">
                    <div class="col-12">
                      <label for="nome" class="form-label">Nome Completo*</label>
                      <input type="text" class="form-control" name="nome" id="nome" placeholder="Ex: João da Silva" value="" required>
                      <div class="invalid-feedback invalid-name">
                        Obrigatório informar o nome completo
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="email" class="form-label">E-mail*</label>
                      <input type="email" class="form-control" name="email" id="email" placeholder="Ex: seuemail@gmail.com" value="" required>
                      <div class="invalid-feedback">
                        Obrigatório informar o e-mail
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="celular" class="form-label">Celular*</label>
                      <input type="phone" class="form-control phone_with_ddd" name="celular" id="celular" placeholder="Ex: 41999999999" value="" required>
                      <div class="invalid-feedback invalid-phone">
                        Insira o DDD e um número de celular válido.
                      </div>
                    </div>

<!--                    <div class="col-12">-->
<!--                        <label for="celular" class="form-label">Empresa</label>-->
<!--                        <input type="phone" class="form-control phone_with_ddd" name="celular" id="celular" placeholder="Ex: 41999999999" value="" required>-->
<!--                        <div class="invalid-feedback invalid-phone">-->
<!--                            Insira o DDD e um número de celular válido.-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                    <div class="col-12">-->
<!--                        <label for="celular" class="form-label">CEP</label>-->
<!--                        <input type="phone" class="form-control phone_with_ddd" name="celular" id="celular" placeholder="Ex: 41999999999" value="" required>-->
<!--                        <div class="invalid-feedback invalid-phone">-->
<!--                            Insira o DDD e um número de celular válido.-->
<!--                        </div>-->
<!--                    </div>-->

<!--                    <div class="col-12">-->
<!--                        <div class="form-check">-->
<!--                            <input type="checkbox" class="form-check-input" id="save-info" required>-->
<!--                            <label class="form-check-label" for="save-info">Ao informar seus dados e seguir para a próxima etapa, você automaticamente concorda com nossa <a href="https://themakers.com.br/politica-de-privacidade/" target="_blank"><a href="https://themakers.com.br/politica-de-privacidade/" target="_blank"><a href="https://themakers.com.br/politica-de-privacidade/" target="_blank">Política de Privacidade</a></label>-->
<!--                        </div>-->
<!--                    </div>-->

                    <p></p>
                    <h5>Dados do cartão</h5>
                    <hr>
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

                    <div class="row" style="padding-right: 0;">
                        <div class="col-6" style="padding:0;">
                            <label for="expiration" class="form-label">Vencimento*</label>
                            <!--                            <input type="text" value="05/2024" id="expiration" name="expiration" class="form-control" placeholder="MM/AAAA">-->
                            <input type="text" value="" id="expiration" name="expiration" class="form-control carddate" placeholder="MM/AAAA" required>

                            <div class="invalid-feedback">
                                Obrigatório informar o vencimento
                            </div>
                        </div>

                        <div class="col-6" style="padding-right:0;">
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
                        <select id="installments" name="installments" style="height: 46px;border: none;" class="form-control">
                            <option value='1'>1 parcela de <?php echo number_format($total / 100, 2, ',', '.'); // converte para reais ?></option>
                            <option value='2'>2 parcelas de <?php echo number_format(($total/2) / 100, 2, ',', '.'); // converte para reais ?></option>
                            <option value='3'>3 parcelas de <?php echo number_format(($total/3) / 100, 2, ',', '.'); // converte para reais ?></option>
                            <option value='4'>4 parcelas de <?php echo number_format(($total/4) / 100, 2, ',', '.'); // converte para reais ?></option>
                            <option value='5'>5 parcelas de <?php echo number_format(($total/5) / 100, 2, ',', '.'); // converte para reais ?></option>
<!--                            <option>6 parcelas de --><?php //echo number_format(($total/6) / 100, 2, ',', '.'); // converte para reais ?><!--</option>-->
                        </select>
                        <div class="invalid-feedback">
                            Obrigatório informar o número de parcelas
                        </div>
                    </div>

                    <input type="hidden" name="total" value="<?php echo $total; ?>">
                    <input type="hidden" name="description" value="<?php echo $services." | ".$description; ?>">

                    <button class="btn btn-primary btn-lg" type="submit">Continuar</button>
                </div>

            </form>
      </div>
    </div>


<script>
    $(document).ready(function() {
        $('form').submit(function(event) {
            var telefone = $('#celular').val();
            var regexTelefone = /[1-9]{2}9[0-9]{8}/;
            var telefone = telefone.replace(/\D/g, '');

            if (!regexTelefone.test(telefone)) {
                $('.invalid-phone').show();
                event.preventDefault(); // impede que o formulário seja enviado
            }
            var nome = $('#nome').val();
            if (nome.split(' ').length < 2) {
                $('.invalid-name').show();
                event.preventDefault(); // impede o envio do formulário
            }

        });

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

    });
</script>

<?php include('footer.php'); ?>
