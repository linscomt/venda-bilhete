<?php
session_start();
$preçoBilhete = 10;
require_once './ControleVendas.php';
require_once './objetos.php';
$controleVendas = new ControleVendas();

$statusSorteio = intval($controleVendas->obterStatusSorteio()[0]);
if($statusSorteio == 0){
    header("Location: ../");
}

if (isset($_SESSION['bilhetesSelecionados'])) {
    $bilhetes = $_SESSION['bilhetesSelecionados'];

    if (count($bilhetes) == 0) {
        header("Location:./");
    }

    
    $bilhetesJaVendidos = array();
    for ($i = 0; $i < count($bilhetes); $i++) {
        if ($controleVendas->isVendido($bilhetes[$i])) {
            array_push($bilhetesJaVendidos, $bilhetes[$i]);
        }
    }

    if (count($bilhetesJaVendidos) > 0) {
        $_SESSION['bilhetesJaVendidos'] = $bilhetesJaVendidos;
        header("Location:./");
    }
} else {
    header("Location: ./");
}

$pessoa = new Pessoa();
if (isset($_POST["nome"])) {
    $pessoa->setNome($_POST["nome"]);
    $pessoa->setEndereco($_POST["endereco"]);
    $pessoa->setTelefone($_POST["telefone"]);
    $pessoa->setEmail($_POST["email"]);
    $pessoa->setAceitaMKT($_POST["aceitaMKT"]);
}

include_once './header.php';
?>

<main id="main">
    <section id="venda-ingresso">
        <div class="container">
            <div class="py-5 text-center">
                <img class="d-block mx-auto mb-4" src="imgs/bilhete.svg" alt="" width="130" height="72">
                <?php
                if (count($bilhetes) == 1) {
                    ?><h2>Preencha seu bilhete</h2>
                    <p class="lead">Agora que você já escolheu o bilhete, preencha seus dados.</p><?php
            } else {
                    ?><h2>Preencha seus bilhetes</h2>
                    <p class="lead">Agora que você já escolheu os bilhetes, preencha seus dados.</p><?php
            }
                ?>
            </div>

            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Seus bilhetes</span>
                        <span class="badge badge-secondary badge-pill"><?= count($bilhetes); ?></span>
                    </h4>
                    <ul class="list-group mb-3">
                        <?php
                        for ($i = 0; $i < count($bilhetes); $i++) {
                            ?>
                            <li class="list-group-item d-flex justify-content-between lh-condensed pr-0">
                                <div>
                                    <h6 class="my-0">Número <?= number_format($bilhetes[$i], 0, '.', '.') ?></h6>
                                    <small class="text-muted">Festa padroeira</small>
                                </div>
                                <div>
                                    <span class="text-muted mr-3">R$<?= $preçoBilhete ?></span>
                                </div>
                            </li>
                        <?php } ?>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total</span>
                            <strong>R$<?= $preçoBilhete * count($bilhetes) ?></strong>
                        </li>
                    </ul>

                    <form class="card p-2">
                        <div class="input-group centered">
                            <input class="btn btn-block btn-outline-secondary" type="submit" value="Escolher mais bilhetes" formaction="./"/>
                        </div>
                    </form>
                </div>
                <div class="col-md-8 order-md-1">
                    <h4 class="mb-3">Preencha seus dados</h4>
                    <form class="needs-validation" novalidate action="pedido" method="POST">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="firstName">Nome completo</label>
                                <input type="text" class="form-control" id="firstName" name="nome" placeholder="João da Silva" value="<?= $pessoa->getNome() ?>" required>
                                <div class="invalid-feedback">
                                    O nome completo é obrigatório.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address">Endereço</label>
                            <input type="text" class="form-control" name="endereco" placeholder="Av. Dom Lino, nº 0, Russas - CE" id="address" value="<?= $pessoa->getEndereco() ?>" required>
                            <div class="invalid-feedback">
                                Digite o seu endereço, ele é obrigatório.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="telefone">Telefone</label>
                            <input type="text" class="form-control phone" name="telefone" placeholder="(88) 99999-9999" data-mask="(00)00000-0000" maxlength="14" id="telefone" value="<?= $pessoa->getTelefone() ?>" required>
                            <small id="telefoneHelp" class="form-text text-muted">Você precisará deste número para consultar seus bilhetes.</small>
                            <div class="invalid-feedback">
                                Digite o seu telefone, ele é obrigatório.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="exemplo@email.com" value="<?= $pessoa->getEmail() ?>" required>
                            <div class="invalid-feedback">
                                Digite um email válido
                            </div>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <?php
                            if ($pessoa->getAceitaMKT()) {
                                ?><input type="checkbox" class="custom-control-input" name="aceitaMKT" id="same-address" checked="checked"><?php
                            } else {
                                ?><input type="checkbox" class="custom-control-input" name="aceitaMKT" id="same-address"><?php
                            }
                            ?>
                            <label class="custom-control-label" for="same-address">Aceito receber informações sobre a paróquia em meu e-mail</label>
                        </div>

                        <hr class="mb-3">

                        <button type="submit" class="btn btn-primary col-12">Próximo</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript">
    var maskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
            options = {onKeyPress: function (val, e, field, options) {
                    field.mask(maskBehavior.apply({}, arguments), options);
                }
            };

    $('.phone').mask(maskBehavior, options);
</script>

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
</style>

<?php
include_once './footer.php';
?>
