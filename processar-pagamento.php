<?php

require_once './ControlePagamento.php';
require_once './objetos.php';
session_start();

//recebimento das informações sobre o pagamento
$amount = $_REQUEST["amount"];
$token = $_REQUEST["token"];
$payment_method_id = $_REQUEST["payment_method_id"];
$installments = $_REQUEST["installments"];
$issuer_id = $_REQUEST["issuer_id"];

//Realizando o pagamento com o Mercado Pago
require_once 'vendor/autoload.php';

MercadoPago\SDK::setAccessToken("APP_USR");
$payment = new MercadoPago\Payment();
$payment->transaction_amount = $amount;
$payment->token = $token;
$payment->description = "Bilhetes Sorteio";
$payment->installments = $installments;
$payment->payment_method_id = $payment_method_id;
$payment->issuer_id = $issuer_id;
$payment->binary_mode = true;
$payment->payer = array(
    "email" => $_SESSION["emailComprador"]
);
// Armazena e envia o pagamento
$payment->save();

//construindo objeto pagamento
$pagamento = new Pagamento();
$pagamento->setIdTransacao($payment->id);
$pagamento->setStatus($payment->status);
$pagamento->setStatus_detail($payment->status_detail);
$pagamento->setDate_approved($payment->date_approved);
$pagamento->setMetodo_pagamento($payment->payment_method_id);
$pagamento->setTipo_pagamento($payment->payment_type_id);

//echo $payment->status;
$cp = new ControlePagamento();
if ((strcmp($payment->status, "approved") == 0) || (strcmp($payment->status, "in_process") == 0)) {
    if ($idPagamento = $cp->registrarDados($_REQUEST["idPessoa"], $pagamento, $_REQUEST["amount"])) {
        echo "entrou";
        if (strcmp($payment->status, "approved") == 0) {
            header("Location: compraRealizada?transacao=" . $idPagamento);
        } else {
            header("Location: emProcessamento?transacao=" . $idPagamento);
        }
    }
} else {
    $cp->removerPessoaBilhete($_REQUEST["idPessoa"]);
    header("Location: pagamentoRecusado?status=" . $payment->status_detail);
}