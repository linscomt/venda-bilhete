<?php

require_once 'vendor/autoload.php';
require_once './ControlePagamento.php';
$cp = new ControlePagamento();

MercadoPago\SDK::setAccessToken("APP_USR");

$merchant_order = null;
switch ($_GET["topic"]) {
    case "payment":
        $payment = MercadoPago\Payment::find_by_id($_GET["id"]);

        $merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
        break;

    case "plan":
        $plan = MercadoPago\Plan . find_by_id($_GET["id"]);
        break;

    case "subscription":
        $plan = MercadoPago\Subscription . find_by_id($_GET["id"]);
        break;

    case "invoice":
        $plan = MercadoPago\Invoice . find_by_id($_GET["id"]);
        break;

    case "merchant_order":
        $merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
        break;
}

$paid_amount = 0;
if ($payment->status == 'approved') {
    $paid_amount += $payment->transaction_amount;
}

if ($paid_amount >= $payment->transaction_amount) {
    if ($merchant_order->shipments > 0) { // The merchant_order has shipments
        if ($merchant_order->shipments[0]->status == "ready_to_ship") {
            print_r("Totally paid. Print the label and release your item.");
        }
    } else { // The merchant_order don't has any shipments
        print_r("Totally paid. Release your item.<br>");
        $external_ref = $payment->external_reference;
        $ext_email = $payment->payer->email;
        $ext_val = $payment->transaction_amount;

        $cp->atualizarPagamento($payment->external_reference);
    }
} else {
    print_r("Not paid yet. Do not release your item.");
}
?>