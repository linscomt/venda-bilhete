<?php
session_start();
$preçoBilhete = 10;

require './ControleVendas.php';
$controleVendas = new ControleVendas();

$statusSorteio = intval($controleVendas->obterStatusSorteio()[0]);
if($statusSorteio == 0){
    header("Location: ../");
}

$controleVendas = new ControleVendas();
$bilhetesVendidos = $controleVendas->obterBilhetesVendidos();

if (isset($_SESSION['bilhetesSelecionados'])) {
    $bilhetes = $_SESSION['bilhetesSelecionados'];
} else {
    $bilhetes = array();
}

//Adicionar bilhete
if (isset($_POST['bilhete'])) {
    $_POST['bilhete'] = str_replace(".", "", $_POST['bilhete']);

    //caso ja tenham bilhetes selecionados antes, verifica se o número já havia sido selecionado
    if (count($bilhetes) > 0) {
        if (!(array_search($_POST['bilhete'], $bilhetes) || strcmp($_POST['bilhete'], $bilhetes[0]) == 0)) {
            array_push($bilhetes, intval($_POST['bilhete']));
            sort($bilhetes);
        }
    } else {
        array_push($bilhetes, intval($_POST['bilhete']));
    }
}

//Remover bilhete
if (isset($_POST['excluir'])) {
    sort($bilhetes);
    $_POST['excluir'] = str_replace(".", "", $_POST['excluir']);
    $id = array_search($_POST['excluir'], $bilhetes);
    array_splice($bilhetes, $id, 1);
}

$_SESSION['bilhetesSelecionados'] = $bilhetes;

include_once './header.php';

//tratando bilhetes que acabaram de ser vendidos
if (isset($_SESSION['bilhetesJaVendidos'])) {
    $controleVendas->alertaBilhetesJaVendidos($bilhetesVendidos);
    for ($i = 0; $i < count($bilhetesVendidos); $i++) {
        sort($bilhetes);

        if (array_search($bilhetesVendidos[$i], $bilhetes) || strcmp($bilhetesVendidos[$i], $bilhetes[0]) == 0) {
            $id = array_search($bilhetesVendidos[$i], $bilhetes);
            array_splice($bilhetes, $id, 1);
        }
    }
    $_SESSION['bilhetesSelecionados'] = $bilhetes;
    unset($_SESSION['bilhetesJaVendidos']);
}
?>

<main id="main">
    <section id="venda-ingresso">
        <div class="container">
            <div class="py-5 text-center">
                <img class="d-block mx-auto mb-4" src="imgs/bilhete.svg" alt="" width="130" height="72">
                <h2>Reserve seu bilhete</h2>
                <p class="lead">Primeiro escolha qual o número do bilhete que você deseja adquirir</p>
            </div>

            <div class="row mt-5">
                <div class="col-md-12 order-md-2">
                    <form class="card p-2" action="./" method="POST">
                        <div class="input-group centered col-pc">
                            <div class="col-md-4 col-sm-12 centered"><input type="button" class="btn btn-sm disabled" value="&emsp; &emsp;" style="background-color: lightgrey;"> &nbsp Vendido</div>
                            <div class="col-md-4 col-sm-12 centered"><input type="button" class="btn btn-sm btn-dark" value="&emsp; &emsp;"> &nbsp Selecionado</div>
                            <div class="col-md-4 col-sm-12 centered"><input type="button" class="btn btn-sm btn-outline-dark" disabled="disabled" value="&emsp; &emsp;"> &nbsp Disponível</div>
                        </div>
                        <div class="input-group centered col-cell">
                            <div class="col-md-4 col-sm-12 mb-1"><input type="button" class="btn btn-sm disabled" value="&emsp; &emsp;" style="background-color: lightgrey;"> &nbsp Vendido</div>
                            <div class="col-md-4 col-sm-12 mb-1"><input type="button" class="btn btn-sm btn-dark" value="&emsp; &emsp;"> &nbsp Selecionado</div>
                            <div class="col-md-4 col-sm-12"><input type="button" class="btn btn-sm btn-outline-dark" disabled="disabled" value="&emsp; &emsp;"> &nbsp Disponível</div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12 order-md-2 mb-4">
                    <form class="card p-2" action="./" method="POST">
                        <div class="input-group centered ml-2 mt-3">
                            <?php
                            $numeroVendas = count($bilhetesVendidos);
                            $bilheteInicial = 0;
                            $bilheteFinal = 0;

                            if ($numeroVendas < 50) {
                                $bilheteInicial = 10001;
                                $bilheteFinal = $bilheteInicial + 50;
                            }

                            if (($numeroVendas >= 50) && ($numeroVendas < 100)) {
                                $bilheteInicial = 10051;
                                $bilheteFinal = $bilheteInicial + 50;
                            }

                            if (($numeroVendas >= 100) && ($numeroVendas < 150)) {
                                $bilheteInicial = 10101;
                                $bilheteFinal = $bilheteInicial + 50;
                            }

                            if (($numeroVendas >= 150) && ($numeroVendas < 200)) {
                                $bilheteInicial = 10151;
                                $bilheteFinal = $bilheteInicial + 50;
                            }

                            if (($numeroVendas >= 200) && ($numeroVendas < 250)) {
                                $bilheteInicial = 10201;
                                $bilheteFinal = $bilheteInicial + 50;
                            }

                            if (($numeroVendas >= 250) && ($numeroVendas < 300)) {
                                $bilheteInicial = 10251;
                                $bilheteFinal = $bilheteInicial + 50;
                            }

                            if (($numeroVendas >= 300) && ($numeroVendas < 350)) {
                                $bilheteInicial = 10301;
                                $bilheteFinal = $bilheteInicial + 50;
                            }

                            if (($numeroVendas >= 350) && ($numeroVendas < 400)) {
                                $bilheteInicial = 10351;
                                $bilheteFinal = $bilheteInicial + 50;
                            }

                            if (($numeroVendas >= 400) && ($numeroVendas < 450)) {
                                $bilheteInicial = 10401;
                                $bilheteFinal = $bilheteInicial + 50;
                            }

                            if (($numeroVendas >= 450) && ($numeroVendas < 500)) {
                                $bilheteInicial = 10451;
                                $bilheteFinal = $bilheteInicial + 50;
                            }


                            for ($i = $bilheteInicial; $i < $bilheteFinal; $i++) {
                                if (in_array($i, $bilhetesVendidos)) {
                                    ?><input type="button" class="btn espacoBilhete disabled" value="<?= number_format($i, 0, '.', '.') ?>" style="background-color: lightgrey;"><?php
                                } else if (in_array($i, $bilhetes)) {
                                    ?><input type="submit" class="btn btn-dark espacoBilhete" name="excluir" value="<?= number_format($i, 0, '.', '.') ?>"><?php
                                } else {
                                    ?><input type="submit" class="btn btn-outline-dark espacoBilhete" name="bilhete" value="<?= number_format($i, 0, '.', '.') ?>"><?php
                                }
                            }
                            ?>
                        </div>
                    </form>

                    <h4 class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <span class="text-muted">Seus bilhetes</span>
                        <span class="badge badge-secondary badge-pill"><?= count($bilhetes); ?></span>
                    </h4>
                    <ul class="list-group mb-3">
                        <form action="./" method="POST">
                            <?php if (count($bilhetes) == 0) { ?>
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div>
                                        <h6 class="my-0">Nenhum bilhete escolhido</h6>
                                    </div>
                                </li>
                                <?php
                            } else {
                                for ($i = 0; $i < count($bilhetes); $i++) {
                                    ?>
                                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                                        <div>
                                            <h6 class="my-0">Número <?= number_format($bilhetes[$i], 0, '.', '.') ?></h6>
                                            <small class="text-muted">Festa padroeira</small>
                                        </div>
                                        <div>
                                            <span class="text-muted">R$<?= $preçoBilhete ?></span>
                                            <button class="btn btn-link" type="submit" name="excluir" value="<?= $bilhetes[$i] ?>"> <i class="fas fa-times-circle" style="color: black;"></i> </button>
                                        </div>
                                    </li>
                                <?php } ?>

                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total</span>
                                    <strong>R$<?= $preçoBilhete * count($bilhetes) ?></strong>
                                </li>

                                <form>
                                    <?php
                                    if (count($bilhetes) == 1) {
                                        ?><input class="btn btn-lg btn-block btn-primary mt-5" style="" type="submit" value="Preencha seu bilhete" formaction="preencherDados"/><?php
                                    } else {
                                        ?><input class="btn btn-lg btn-block btn-primary mt-5" type="submit" value="Preencha seus bilhetes" formaction="preencherDados"/><?php
                                    }
                                    ?>
                                </form>
                            <?php } ?>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</main>

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

    @media screen and (max-width: 767px) {
        .col-pc{
            display: none;
        }

        .col-cell{
            display: flex;
        }
    }

    @media screen and (min-width: 767px) {
        .col-pc{
            display: flex;
        }

        .col-cell{
            display: none;
        }
    }
</style>

<?php
include_once './footer.php';
?>