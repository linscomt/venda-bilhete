<?php
session_start();
$preçoBilhete = 10;

//verificando se foram selecionados bilhetes
if (isset($_SESSION['bilhetesSelecionados'])) {
    $bilhetes = $_SESSION['bilhetesSelecionados'];
} else {
    header("Location: ./");
}

include_once './header.php';
?>

<main id="main">
    <section id="venda-ingresso">
        <div class="container">
            <div class="py-5 text-center">
                <img class="d-block mx-auto mb-4" src="imgs/check-warning.svg" alt="" width="120" height="120">
                <h2>Ops... houve algum problema com o seu pagamento</h2>
                <p class="lead">O pagamento foi recusado. Caso tenha alguma cobrança em seu cartão, em breve será estornada.</p>
                <p class="lead">Suporte via Whatsapp: <a href="https://api.whatsapp.com/send?phone=558897437872">(88) 99743-7872</a></p>
                <p class="lead">Status: <?=$_GET["status"]?></p>
                <form action="processar-pagamento.php" method="POST">
                    <script
                        src="https://www.mercadopago.com.br/integrations/v1/web-tokenize-checkout.js"
                        data-public-key="APP_USR"
                        data-max-installments="1"
                        data-button-label="Tentar novamente"
                        data-transaction-amount="<?= $preçoBilhete * count($bilhetes) ?>">
                    </script>
                </form>
            </div>
        </div>
    </section>
</main>



<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="../../assets/js/vendor/popper.min.js"></script>
<script src="../../dist/js/bootstrap.min.js"></script>
<script src="../../assets/js/vendor/holder.min.js"></script>
<script>
                            // Example starter JavaScript for disabling form submissions if there are invalid fields
                            (function () {
                                'use strict';

                                window.addEventListener('load', function () {
                                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                                    var forms = document.getElementsByClassName('needs-validation');

                                    // Loop over them and prevent submission
                                    var validation = Array.prototype.filter.call(forms, function (form) {
                                        form.addEventListener('submit', function (event) {
                                            if (form.checkValidity() === false) {
                                                event.preventDefault();
                                                event.stopPropagation();
                                            }
                                            form.classList.add('was-validated');
                                        }, false);
                                    });
                                }, false);
                            })();
</script>

<style>
    .centered{
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center
    }

    .espacoBilhete{
        margin-right: 15px;
        margin-bottom: 15px;
    }

    .bilhete{
        background-image: url(assets/bilhete.png);
        background-size: cover;
        min-height: 400px;
        min-width: 700px;
    }
</style>

<?php
include_once './footer.php';
?>