<?php
session_start();
$preçoBilhete = 10;
include_once './objetos.php';
require_once './ControlePagamento.php';

//verificando se foram selecionados bilhetes
if (isset($_SESSION['bilhetesSelecionados'])) {
    $bilhetes = $_SESSION['bilhetesSelecionados'];
} else {
    header("Location: ./");
}

//verificando se os dados obrigatórios do formulário vieram
if (!(isset($_POST["nome"]) && isset($_POST["endereco"]) && isset($_POST["telefone"]) && isset($_POST["email"]))) {
    header("Location: ./preencherDados");
}

//construindo objeto pessoa
$pessoa = new Pessoa();
$pessoa->setNome($_POST["nome"]);
$pessoa->setEndereco($_POST["endereco"]);
$pessoa->setTelefone($_POST["telefone"]);
$pessoa->setEmail($_POST["email"]);
$_SESSION["emailComprador"] = $_POST["email"];
if (isset($_POST["aceitaMKT"])) {
    $pessoa->setAceitaMKT("TRUE");
}

//salvando objeto pessoa na sessão
$cp = new ControlePagamento();

if ($idPessoaCriada = $cp->registrarPessoaBilhete($pessoa, $bilhetes)) {

// SDK de Mercado Pago
    require __DIR__ . '/vendor/autoload.php';

// Configura credenciais
    MercadoPago\SDK::setAccessToken("APP_USR");

// Cria um objeto de preferência
    $preference = new MercadoPago\Preference();

// Cria um item na preferência
    $item = new MercadoPago\Item();
    $item->title = 'Bilhetes Sorteio';
    $item->quantity = count($bilhetes);
    $item->unit_price = $preçoBilhete;
    $preference->items = array($item);

    $preference->back_urls = array(
        "success" => "processar-pagamento?status=sucesso",
        "failure" => "processar-pagamento?status=falha",
        "pending" => "processar-pagamento?status=pendente"
    );
    $preference->save();



    include_once './header.php';
    ?>

    <main id="main">
        <section id="venda-ingresso">
            <div class="container">
                <div class="py-5 text-center">
                    <img class="d-block mx-auto mb-4" src="imgs/bilhete.svg" alt="" width="130" height="72">
                    <h2>Resumo do seu pedido</h2>
                    <p class="lead">Verifique os dados informados e efetue o pagamento</p>
                </div>

                <div class="row order-md-1">

                    <div class="col-md-6">
                        <?php
                        if (count($bilhetes) > 1) {
                            ?> <h4 class="mb-3">Bilhetes selecionados:</h4> <?php
                        } else {
                            ?> <h4 class="mb-3">Bilhete selecionado:</h4> <?php
                        }
                        ?>

                        <div class="col-md-12 mb-3">
                            <?php
                            for ($i = 0; $i < count($bilhetes); $i++) {
                                ?><input type="button" class="btn btn-dark espacoBilhete" value="<?= number_format($bilhetes[$i], 0, '.', '.') ?>"><?php
                            }
                            ?>

                            <div>
                                Total: R$ <?= $preçoBilhete * count($bilhetes) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h4 class="mb-3">Comprador:</h4>
                        <div class="col-md-12 mb-3">
                            <i><?= $pessoa->getNome() ?></i> <br>
                            <i><?= $pessoa->getEndereco() ?></i><br>
                            <i><?= $pessoa->getTelefone() ?></i><br>
                            <i><?= $pessoa->getEmail() ?></i> 
                        </div>
                    </div>

                    <hr class="mb-12">
                </div>

                <div class="row">
                    <div class="col-md-6 mt-3 visualizacaoCelular">
                        <form class="col-md-6" action="venda-bilhete/processar-pagamento.php" method="POST">
                            <input type="hidden" name="amount" id="amount" value="<?= $preçoBilhete * count($bilhetes) ?>">
                            <input type="hidden" name="idPessoa" id="amount" value="<?= $idPessoaCriada ?>">

                            <script
                                data-summary-product-label="<?= count($bilhetes) ?> bilhetes"
                                data-summary-product="<?= $preçoBilhete * count($bilhetes) ?>"
                                src="https://www.mercadopago.com.br/integrations/v1/web-tokenize-checkout.js"
                                data-public-key="APP_USR"
                                data-max-installments="1"
                                data-button-label="Efetuar Pagamento"
                                data-transaction-amount="<?= $preçoBilhete * count($bilhetes) ?>">

                            </script>
                        </form>
                    </div>
                    
                    <div class="col-md-6 mt-3 visualizacaoPC">
                        <form class="col-md-6" action="processar-pagamento.php" method="POST">
                            <input type="hidden" name="amount" id="amount" value="<?= $preçoBilhete * count($bilhetes) ?>">
                            <input type="hidden" name="idPessoa" id="amount" value="<?= $idPessoaCriada ?>">

                            <script
                                data-summary-product-label="<?= count($bilhetes) ?> bilhetes"
                                data-summary-product="<?= $preçoBilhete * count($bilhetes) ?>"
                                src="https://www.mercadopago.com.br/integrations/v1/web-tokenize-checkout.js"
                                data-public-key="APP_USR"
                                data-max-installments="1"
                                data-button-label="Efetuar Pagamento"
                                data-transaction-amount="<?= $preçoBilhete * count($bilhetes) ?>">

                            </script>
                        </form>
                    </div>

                    <div class="col-md-6 mt-3">
                        <form class="col-md-6" action="preencherDados" method="POST">
                            <input type="hidden" name="idPessoa" value="<?= $idPessoaCriada ?>">
                            <input type="hidden" name="nome" value="<?= $pessoa->getNome() ?>">
                            <input type="hidden" name="endereco" value="<?= $pessoa->getEndereco() ?>">
                            <input type="hidden" name="telefone" value="<?= $pessoa->getTelefone() ?>">
                            <input type="hidden" name="email" value="<?= $pessoa->getEmail() ?>">
                            <input type="hidden" name="aceitaMKT" value="<?= $pessoa->getAceitaMKT() ?>">
                            <button type="submit" class="btn btn-secondary col-12">Editar dados preenchidos</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>       
    </main>




    <style>
        button.mercadopago-button {
            color: #fff;
            background-color: #5889bd;
            border-color: #ffffff;
            display: block;
            width: 100%;
            padding: .5rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .3rem;
        }

        .espacoBilhete{
            margin-right: 15px;
            margin-bottom: 15px;
        }

        .centered{
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center
        }

        @media screen and (max-width: 767px) {
            .visualizacaoCelular{
                display: flex;
            }

            .visualizacaoPC{
                display: none;
            }
        }

        @media screen and (min-width: 767px) {
            .visualizacaoCelular{
                display: none;
                background-color: #fff;
            }

            .visualizacaoPC{
                display: flex;
            }
        }
    </style>
    <?php
}
include_once './footer.php';
?>