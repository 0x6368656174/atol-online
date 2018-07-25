# it-quasar/atol-online

Библиотека для работы с [АТОЛ Онлайн](https://online.atol.ru/).

Библиотека содержит набор классов для формирования запросов в АТОЛ Онлайн и обработки ответов из АТОЛ Онлайн.
Все классы сделаны таким образом, чтоб их названия и свойства максимально соответствовали [официальной документации
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
use ItQuasar\AtolOnline\Sell;
use ItQuasar\AtolOnline\Service;
use ItQuasar\AtolOnline\SnoSystem;
use ItQuasar\AtolOnline\TaxSystem;

// Создадим время заказа
$timestamp = new DateTime();
$timestamp
  ->setDate(2017, 05, 29)
  ->setTime(17, 56, 18);
  
// Создадим запрос на продажу
// Параметры запроса соответствуют параметрам запроса, описанным в 
// https://online.atol.ru/files/АТОЛ%20Онлайн._Описание%20протокола.pdf
$request = new Sell();
$request
  ->setExternalId('17052917561851307')
  ->setTimestamp($timestamp);

// Создадим сервисный раздел запроса
$service = new Service();
$service
  ->setCallbackUrl('http://example.com/callback')
  ->setInn('331122667723')
  ->setPaymentAddress('http://example.com');

// Добавим в запрос сервисный раздел
$request->setService($service);

// Создадим чек
$receipt = new Receipt();
$receipt->setTotal(7612);

// Добавим в запрос чек
$request->setReceipt($receipt);

// Создадим атрибуты чека
$attributes = new ReceiptAttributes();
$attributes
  ->setEmail('mail@example.com')
  ->setSno(SnoSystem::OSN);

// Добавим в чек атрибуты
$receipt->setAttributes($attributes);

// Создадим первую позицию
$item1 = new ReceiptItem('Название товара 1');
$item1
  ->setPrice(5000)
  ->setQuantity(1)
  ->setSum(5000)
  ->setTax(TaxSystem::VAT10)
  ->setTaxSum(454.55);

// Добавим в чек первую позицию
$receipt->addItem($item1);

// Создадим вторую позицию
$item2 = new ReceiptItem('Название товара 2');
$item2
  ->setPrice(1456.21)
  ->setQuantity(2)
  ->setSum(2612.42)
  ->setTax(TaxSystem::VAT118)

// Добавим в чек вторую позицию
$receipt->addItem($item2);

// Создадим оплату
$payment = new Payment();
$payment
  ->setSum(7612)
  ->setType(1);

// Добавим в чек оплату
$receipt->addPayment($payment);

// PSR-совместимый логгер (опциональный параметр)
$logger = null;

// Логин, пароль и код группы можно найти в "Настройках интергатора", скачиваемых с 
// личного кабинета АТОЛ Онлайн в ноде <access>
$login = 'netletest';
$passwor = 'v2AfscRjr';
$groupCode = 'netletest_8491';

// Создадим клиент
$client = new Client($login, $password, $groupCode, $logger);

// Отравим запрос
// $uuid будет содержать UUID документа в системе АТОЛ Онлайн
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

// PSR-совместимый логгер (опциональный параметр)
$logger = null;

// Логин, пароль и код группы можно найти в "Настройках интергатора", скачиваемых с 
// личного кабинета АТОЛ Онлайн в ноде <access>
$login = 'netletest';
$passwor = 'v2AfscRjr';
$groupCode = 'netletest_8491';

// Создадим клиент
$client = new Client($login, $password, $groupCode, $logger);

// UUID документа, полученный при регистрации документа в системе АТОЛ Онлайн
$uuid = '...';

// Отравим запрос на получение статуса обработки.
// $report будет содержать ItQuasar/AtolOnline/Report,
// который соответствует структуре описанной в 
// https://online.atol.ru/files/АТОЛ%20Онлайн._Описание%20протокола.pdf
$report = $client->getReport($uuid);
```
