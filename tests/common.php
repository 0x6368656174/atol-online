<?php
/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use ItQuasar\AtolOnline\Buy;
use ItQuasar\AtolOnline\BuyCorrection;
use ItQuasar\AtolOnline\BuyRefund;
use ItQuasar\AtolOnline\Correction;
use ItQuasar\AtolOnline\CorrectionCompany;
use ItQuasar\AtolOnline\CorrectionInfo;
use ItQuasar\AtolOnline\Payment;
use ItQuasar\AtolOnline\PaymentMethod;
use ItQuasar\AtolOnline\PaymentObject;
use ItQuasar\AtolOnline\Receipt;
use ItQuasar\AtolOnline\Client;
use ItQuasar\AtolOnline\Company;
use ItQuasar\AtolOnline\Item;
use ItQuasar\AtolOnline\Sell;
use ItQuasar\AtolOnline\SellCorrection;
use ItQuasar\AtolOnline\SellRefund;
use ItQuasar\AtolOnline\Service;
use ItQuasar\AtolOnline\SnoSystem;
use ItQuasar\AtolOnline\VatType;
use ItQuasar\AtolOnline\Vat;

/**
 * @param Sell|Buy|SellRefund|BuyRefund $request
 *
 * @throws Exception
 */
function createBuyRequest(&$request) {
  $request->setExternalId('17052917561851307');

  $receipt = new Receipt();
  $request->setReceipt($receipt);

  $client = new Client();
  $receipt->setClient($client);
  $client->setEmail('kkt@kkt.ru');

  $company = new Company();
  $receipt->setCompany($company);
  $company->setEmail('chek@romashka.ru');
  $company->setSno(SnoSystem::OSN);
  $company->setInn('1234567891');
  $company->setPaymentAddress('http://magazin.ru/');

  $vat18 = new Vat();
  $vat18->setType(VatType::VAT18);

  $vat10 = new Vat();
  $vat10->setType(VatType::VAT10);

  $item1 = new Item('колбаса Клинский Брауншвейгская с/к в/с ');
  $receipt->addItem($item1);
  $item1->setPrice(1000);
  $item1->setQuantity(0.3);
  $item1->setSum(300);
  $item1->setPaymentObject(PaymentObject::COMMODITY);
  $item1->setPaymentMethod(PaymentMethod::FULL_PAYMENT);
  $item1->setVat($vat18);
  $item1->setMeasurementUnit('кг');

  $item2 = new Item('яйцо Окское куриное С0 белое');
  $receipt->addItem($item2);
  $item2->setPrice(100);
  $item2->setQuantity(1);
  $item2->setSum(100);
  $item2->setPaymentObject(PaymentObject::COMMODITY);
  $item2->setPaymentMethod(PaymentMethod::FULL_PAYMENT);
  $item2->setVat($vat10);
  $item2->setMeasurementUnit('Упаковка 10 шт.');

  $payment = new Payment();
  $receipt->addPayment($payment);
  $payment->setSum(400);
  $payment->setType(1);

  $vat1 = new Vat();
  $vat1->setType(VatType::VAT18);
  $vat1->setSum(45.76);
  $receipt->addVat($vat1);

  $vat2 = new Vat();
  $vat2->setType(VatType::VAT10);
  $vat2->setSum(9.09);
  $receipt->addVat($vat2);

  $receipt->setTotal(400);

  $service = new Service();
  $request->setService($service);
  $service->setCallbackUrl('http://testtest');

  $timestamp = new DateTime();
  $timestamp->setDate(2017, 02, 1);
  $timestamp->setTime(13, 45, 00);
  $request->setTimestamp($timestamp);
}

/**
 * @param $request BuyCorrection|SellCorrection
 *
 * @throws Exception
 */
function createCorrectionRequest(&$request) {
  $request->setExternalId('17052917561851307');

  $correction = new Correction();
  $request->setCorrection($correction);

  $company = new CorrectionCompany();
  $correction->setCompany($company);
  $company->setSno(SnoSystem::OSN);
  $company->setInn('331122667723');
  $company->setPaymentAddress('magazin.ru');

  $correctionInfo = new CorrectionInfo();
  $correction->setCorrectionInfo($correctionInfo);
  $correctionInfo->setType(CorrectionInfo::TYPE_SELF);

  $baseDate = new DateTime();
  $baseDate->setDate(2017, 07, 25);
  $correctionInfo->setBaseDate($baseDate);

  $correctionInfo->setBaseNumber('1175');
  $correctionInfo->setBaseName('Акт технического заключения');

  $payment = new Payment();
  $correction->addPayment($payment);
  $payment->setSum(2000);
  $payment->setType(Payment::TYPE_ELECTRONIC);

  $vat18 = new Vat();
  $correction->addVat($vat18);
  $vat18->setType(VatType::VAT18);
  $vat18->setSum(10);

  $vat10 = new Vat();
  $correction->addVat($vat10);
  $vat10->setType(VatType::VAT10);
  $vat10->setSum(20);

  $service = new Service();
  $request->setService($service);
  $service->setCallbackUrl('http://testtest');

  $timestamp = new DateTime();
  $timestamp->setDate(2017, 05, 29);
  $timestamp->setTime(17, 56, 18);
  $request->setTimestamp($timestamp);
}
