<?php

require_once 'vendor/autoload.php';

use ItQuasar\AtolOnline\Buy;
use ItQuasar\AtolOnline\Client;
use ItQuasar\AtolOnline\Payment;
use ItQuasar\AtolOnline\Receipt;
use ItQuasar\AtolOnline\ReceiptAttributes;
use ItQuasar\AtolOnline\ReceiptItem;
use ItQuasar\AtolOnline\Service;
use ItQuasar\AtolOnline\SnoSystem;
use ItQuasar\AtolOnline\TaxSystem;

$client = new Client('kino-khv-ru', 'i8w0P28cU', 'kino-khv-ru_836');

var_dump($client);

