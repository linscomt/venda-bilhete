<?php
require_once './ControleVendas.php';
require_once 'vendor/autoload.php';
require_once './objetos.php';

$cv = new ControleVendas();
$vendas = $cv->agruparDadosBilhetePorTelefone($cv->obterBilhetesPeloTelefone($_POST["telefone"]));
$html = "";

$mpdf = new \Mpdf\Mpdf();
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents('estiloImprimirBilhete.css');
$mpdf->WriteHTML($css, 1);
$mpdf->WriteHTML($html);
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../../../favicon.ico">

        <title>Paróquia de Russas - Nossa Senhora do Rosário</title>

        <!-- Favicons -->
        <link href="../img/favicon.png" rel="icon">
        <link href="../img/favicon.png" rel="apple-touch-icon">

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

        <!-- Custom styles for this template -->
        <link href="form-validation.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/9937654b0d.js" crossorigin="anonymous"></script>
    </head>

    <body>

        <?php
        for ($i = 0; $i < count($vendas[0]); $i++) {
            $html = '<h2 class="text-center">PARÓQUIA NOSSA SENHORA DO ROSÁRIO</h2>
                <h4 class="text-center">Av. Dom Lino, 784 - Russas - CE</h4>
                <h5 class="text-center">Telefone: (88) 3411-0166 E-mail: paroquiarussascom@gmail.com</h5>
                <br>';

            $html = $html . '<h3 class="">Dados do Comprador</h3>
                <table class="tg ">
                    <tbody>
                        <tr>
                            <th class="tg-0lax text-center"><strong>Nome</strong></th>
                            <th class="tg-0lax text-center"><strong>Endereço</strong></th>
                            <th class="tg-0lax text-center"><strong>Telefone</strong></th>
                            <th class="tg-0lax text-center"><strong>Email</strong></th>
                        </tr>
                        <tr>
                            <td class="tg-0lax">' . $vendas[0][$i]->getNome() . '</td>
                            <td class="tg-0lax">' . $vendas[0][$i]->getEndereco() . '</td>
                            <td class="tg-0lax">' . $vendas[0][$i]->getTelefone() . '</td>
                            <td class="tg-0lax">' . $vendas[0][$i]->getEmail() . '</td>
                        </tr>
                    </tbody>
                </table>';

            $html = $html . '<h3 class="">Dados do Pagamento</h3>
                <table class="tg">
                    <tbody>
                        <tr>
                            <th class="tg-0lax text-center"><strong>Transação</strong></th>
                            <th class="tg-0lax text-center"><strong>Forma de pagamento</strong></th>
                            <th class="tg-0lax text-center"><strong>Data</strong></th>
                            <th class="tg-0lax text-center"><strong>Status</strong></th>
                            <th class="tg-0lax text-center"><strong>Valor</strong></th>
                        </tr>
                        <tr>
                            <td class="tg-0lax text-center">' . $vendas[1][$i]->getIdTransacao() . '</td>
                            <td class="tg-0lax">Cartão de Crédito - ' . $vendas[1][$i]->getMetodo_pagamento() . '</td>
                            <td class="tg-0lax">' . $vendas[1][$i]->getDate_approved() . '</td>
                            <td class="tg-0lax">Pagamento Aprovado</td>
                            <td class="tg-0lax">R$ ' . $vendas[1][$i]->getValor() . '</td>
                        </tr>
                    </tbody>
                </table>';

            for ($j = 0; $j < count($vendas[2]); $j++) {
                if ($vendas[2][$j]->getIdPessoa() == $vendas[0][$i]->getIdPessoa() && $vendas[2][$j]->getIdPagamento() == $vendas[1][$i]->getIdPagamento()) {
                    $topImg = 0;
                    $topNum = 5;

                    $html = $html . '
                    <div> <br>
                    <strong style="z-index: 1; position: absolute; top: ' . $topNum . 'px; left: 615px; font-size: 1rem"> Bilhete Número: ' . number_format($vendas[2][$j]->getNumero(), 0, '.', '.') . '</strong>
                        <br><img src="imgs/bilhete.png" style="max-height: 240px; position: absolute; left: 0px; top: ' . $topImg . 'px; z-index: -1;">
                    </div>';

                    $topImg = $topImg + 460;
                    $topNum = $topNum + 460;
                }
            }

            $mpdf->WriteHTML($html);

            if ($i != (count($vendas[0]) - 1)) {
                $mpdf->AddPage();
            }
        }
        ?>
    </body>

</html>

<?php
$mpdf->Output();
?>

