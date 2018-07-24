# it-quasar/atol-online

Библиотека для работы с [АТОЛ Онлайн](https://online.atol.ru/).

Библиотека содержит набор классов для формирования запросов в АТОЛ Онлайн и обработки ответов из АТОЛ Онлайн.
Все классы сделаны таким образом, чтоб их названия и свойства максимально соответствовали [официально документации
АТОЛ Онлайн](https://online.atol.ru/files/%D0%90%D0%A2%D0%9E%D0%9B%20%D0%9E%D0%BD%D0%BB%D0%B0%D0%B8%CC%86%D0%BD._%D0%9E%D0%BF%D0%B8%D1%81%D0%B0%D0%BD%D0%B8%D0%B5%20%D0%BF%D1%80%D0%BE%D1%82%D0%BE%D0%BA%D0%BE%D0%BB%D0%B0.pdf).

## Установка

Пакет доступен для установки при помощи пакетного менеджера Composer:

```.sh
$ composer require it-quasar/atol-online
```

## Использование

Для регистрации документа в ККТ необходимо выполнить следующий код:

```.php
<?php

use ItQuasar\AtolOnline\Client;
use ItQuasar\AtolOnline\Payment;
use ItQuasar\AtolOnline\Receipt;
use ItQuasar\AtolOnline\ReceiptAttributes;
use ItQuasar\AtolOnline\ReceiptItem;
use ItQuasar\AtolOnline\Buy;
use ItQuasar\AtolOnline\Service;
use ItQuasar\AtolOnline\SnoSystem;
use ItQuasar\AtolOnline\TaxSystem;

// Создадим запрос на продажу
// Параметры запроса соответствуют параметрам запроса, описанным в 
// https://online.atol.ru/files/АТОЛ%20Онлайн._Описание%20протокола.pdf
$request = new Buy();
$request->setExternalId('17052917561851307');

$timestamp = new DateTime();
$timestamp->setDate(2017, 05, 29);
$timestamp->setTime(17, 56, 18);

$request->setTimestamp($timestamp);

$receipt = new Receipt();
$receipt->setTotal(7612);

$request->setReceipt($receipt);

$attributes = new ReceiptAttributes();
$attributes->setEmail('mail@example.com');
$attributes->setSno(SnoSystem::OSN);

$receipt->setAttributes($attributes);

$item1 = new ReceiptItem('Название товара 1');
$item1->setPrice(5000);
$item1->setQuantity(1);
$item1->setSum(5000);
$item1->setTax(TaxSystem::VAT10);
$item1->setTaxSum(454.55);

$receipt->addItem($item1);

$item2 = new ReceiptItem('Название товара 2');
$item2->setPrice(1456.21);
$item2->setQuantity(2);
$item2->setSum(2612.42);
$item2->setTax(TaxSystem::VAT118);

$receipt->addItem($item2);

$payment = new Payment();
$payment->setSum(7612);
$payment->setType(1);

$receipt->addPayment($payment);

$service = new Service();
$service->setCallbackUrl('http://example.com/callback');
$service->setInn('331122667723');
$service->setPaymentAddress('example.com');

$request->setService($service);

// PSR-совместимый логгер (опциональный параметр)
$logger = null;

// Создадим клиент
$client = new Client('netletest', 'v2AfscRjr', 'netletest_8491', $logger);

// Отравим запрос, вернет UUID документа в системе АТОЛ Онлайн
$uuid = $client->send($request);
```

Доступные запросы:
* `ItQuasar/AtolOnline/Sell` - чек «Приход»;
* `ItQuasar/AtolOnline/SellRefund` - чек «Возврат прихода»;
* `ItQuasar/AtolOnline/SellCorrection` - чек «Коррекция прихода»;
* `ItQuasar/AtolOnline/Buy` - чек «Расход»;
* `ItQuasar/AtolOnline/BuyRefund` - чек «Возврат расхода»;
* `ItQuasar/AtolOnline/BuyCorrection` - чек «Коррекция расхода».


Для получения статуса обработки документа необходимо выполнить следующий код:

```.php
<?php

use ItQuasar\AtolOnline\Client;

// Создадим клиент
$client = new Client('netletest', 'v2AfscRjr', 'netletest_8491', $logger);

// UUID, полученный при регистрации документа в системе АТОЛ Онлайн
$uuid = '...';

// Отравим запрос, на получение статуса обработки
// report будет содержать в себе ItQuasar/AtolOnline/Report 
// Который соответствует структуре описанной в 
// https://online.atol.ru/files/АТОЛ%20Онлайн._Описание%20протокола.pdf
$report = $client->getReport($uuid);
```
