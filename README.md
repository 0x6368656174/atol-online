# it-quasar/atol-online

Библиотека на PHP для работы с [АТОЛ Онлайн v4](https://online.atol.ru/).

Библиотека содержит набор классов на PHP для формирования запросов в АТОЛ Онлайн и обработки ответов из АТОЛ Онлайн.
Все классы сделаны таким образом, чтоб их названия и свойства максимально соответствовали [официальной документации
АТОЛ Онлайн v4](https://raw.githubusercontent.com/0x6368656174/atol-online/master/api/atol-online-v4.6.pdf).

## Установка

Пакет доступен для установки при помощи пакетного менеджера Composer:

```.sh
$ composer require it-quasar/atol-online
```

### Зависимости

Библиотека использует в своей работы стандартный PSR-совместимый кеш (см. http://www.php-cache.com) для хранения 
временного ключа доступа к API АТОЛ Онлайн.

Поддерживается любой PSR-совместимый кеш, например, можно использовать файловый кеш (https://github.com/php-cache/filesystem-adapter).
Для этого его необходимо установить при помощи Composer:

```.sh
$ composer require cache/filesystem-adapter
```

## Использование

Для регистрации документа в ККТ необходимо выполнить следующий код:

```.php
<?php

// Классы PSR-совместимого кеша (в данном примере используется Filesystem кеш, может быть любой другой)
use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

use ItQuasar\AtolOnline\AtolClient;
use ItQuasar\AtolOnline\Client;
use ItQuasar\AtolOnline\Company;
use ItQuasar\AtolOnline\Item;
use ItQuasar\AtolOnline\Payment;
use ItQuasar\AtolOnline\Receipt;
use ItQuasar\AtolOnline\Sell;
use ItQuasar\AtolOnline\Service;
use ItQuasar\AtolOnline\SnoSystem;
use ItQuasar\AtolOnline\Vat;
use ItQuasar\AtolOnline\VatType;

// Создадим время заказа
$timestamp = new DateTime();
$timestamp
  ->setDate(2017, 05, 29)
  ->setTime(17, 56, 18);
  
// Создадим запрос на продажу
// Параметры запроса соответствуют параметрам запроса, описанным в 
// https://raw.githubusercontent.com/0x6368656174/atol-online/master/api/atol-online-v4.6.pdf
$request = new Sell();
$request
  ->setExternalId('17052917561851307')
  ->setTimestamp($timestamp);

// Создадим чек
$receipt = new Receipt();
$receipt->setTotal(7612);

// Установми чек для запроса
$request->setReceipt($receipt);

// Создадим атрибуты клиента
$client = new Client();
$client->setEmail('client@example.com');

// Установим атрибуты клиента для чека
$receipt->setClient($client);

// Создадим атрибуты компании
$company = new Company();
$company
  ->setEmail('shop@example.com')
  ->setSno(SnoSystem::OSN)
  ->setInn('331122667723')
  ->setPaymentAddress('http://example.com');
  
// Установим атрибуты компании для чека
$receipt->setCompany($company);

// Создадим атрибут налога под 20% НДС
$vat20 = new Vat();
$vat20->setType(VatType::VAT20);

// Создадим первую позицию
$item1 = new Item('Название товара 1');
$item1
  ->setPrice(5000)
  ->setQuantity(1)
  ->setSum(5000)
  ->setVat($vat20)
  ->setPaymentObject(PaymentObject::COMMODITY)
  ->setPaymentMethod(PaymentMethod::FULL_PAYMENT);
  

// Добавим в чек первую позицию
$receipt->addItem($item1);

// Создадим вторую позицию
$item2 = new Item('Название товара 2');
$item2
  ->setPrice(1456.21)
  ->setQuantity(2)
  ->setVat($vat20)
  ->setPaymentObject(PaymentObject::COMMODITY)
  ->setPaymentMethod(PaymentMethod::FULL_PAYMENT)
  ->setMeasurementUnit('кг');

// Добавим в чек вторую позицию
$receipt->addItem($item2);

// Создадим оплату
$payment = new Payment();
$payment
  ->setSum(7612)
  ->setType(1);

// Добавим в чек оплату
$receipt->addPayment($payment);

// Создадим служебный раздел
$service = new Service();
$service->setCallbackUrl('http://example.com/payment-result');

// Установим служебный раздел для запроса на продажу
$request->setService($service);

// PSR-совместимый интерфейс кеширования, см. http://www.php-cache.com
// В данном случае используется Filesystem кеш, настроим его пул и получим итем для кеширования
$filesystemAdapter = new Local(__DIR__.'/');
$filesystem = new Filesystem($filesystemAdapter);
$pool = new FilesystemCachePool($filesystem);
$cache = $pool->getItem('atol');

// PSR-совместимый логгер (опциональный параметр)
$logger = null;

// Логин, пароль и код группы можно найти в "Настройках интергатора", скачиваемых с 
// личного кабинета АТОЛ Онлайн в ноде <access>
$login = 'netletest';
$passwor = 'v2AfscRjr';
$groupCode = 'netletest_8491';

// Создадим клиент
$client = new AtolClient($login, $password, $groupCode, $cache, $logger);

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

// Классы PSR-совместимого кеша (в данном примере используется Filesystem кеш, может быть любой другой)
use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

use ItQuasar\AtolOnline\AtolClient;
use ItQuasar\AtolOnline\Report;

// PSR-совместимый интерфейс кеширования, см. http://www.php-cache.com
// В данном случае используется Filesystem кеш, настроим его пул и получим итем для кеширования
$filesystemAdapter = new Local(__DIR__.'/');
$filesystem = new Filesystem($filesystemAdapter);
$pool = new FilesystemCachePool($filesystem);
$cache = $pool->getItem('atol');

// PSR-совместимый логгер (опциональный параметр)
$logger = null;

// Логин, пароль и код группы можно найти в "Настройках интергатора", скачиваемых с 
// личного кабинета АТОЛ Онлайн в ноде <access>
$login = 'netletest';
$passwor = 'v2AfscRjr';
$groupCode = 'netletest_8491';

// Создадим клиент
$client = new AtolClient($login, $password, $groupCode, $cache, $logger);

// UUID документа, полученный при регистрации документа в системе АТОЛ Онлайн
$uuid = '...';

// Отравим запрос на получение статуса обработки.
// $report будет содержать ItQuasar/AtolOnline/Report,
// который соответствует структуре описанной в 
// https://raw.githubusercontent.com/0x6368656174/atol-online/master/api/atol-online-v4.6.pdf
$report = $client->getReport($uuid);
```
