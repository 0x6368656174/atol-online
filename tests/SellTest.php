<?php

declare(strict_types=1);

/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ItQuasar\AtolOnline\Buy;
use ItQuasar\AtolOnline\Payment;
use ItQuasar\AtolOnline\Receipt;
use ItQuasar\AtolOnline\ReceiptAttributes;
use ItQuasar\AtolOnline\ReceiptItem;
use ItQuasar\AtolOnline\Sell;
use ItQuasar\AtolOnline\Service;
use ItQuasar\AtolOnline\SnoSystem;
use ItQuasar\AtolOnline\TaxSystem;
use PHPUnit\Framework\TestCase;

final class SellTest extends TestCase
{
  public function testCanReturnValidArray()
  {
    $dir = __DIR__;
    $expectedDateJson = file_get_contents("$dir/buy-request.json");
    $expectedDate = json_decode($expectedDateJson, true);

    $request = new Sell();
    $request->setExternalId('17052917561851307');

    $timestamp = new DateTime();
    $timestamp->setDate(2017, 05, 29);
    $timestamp->setTime(17, 56, 18);
    $request->setTimestamp($timestamp);

    $receipt = new Receipt();
    $request->setReceipt($receipt);
    $receipt->setTotal(7612);

    $attributes = new ReceiptAttributes();
    $receipt->setAttributes($attributes);
    $attributes->setEmail('mail@example.com');
    $attributes->setSno(SnoSystem::OSN);

    $item1 = new ReceiptItem('Название товара 1');
    $receipt->addItem($item1);
    $item1->setPrice(5000);
    $item1->setQuantity(1);
    $item1->setSum(5000);
    $item1->setTax(TaxSystem::VAT10);
    $item1->setTaxSum(454.55);

    $item2 = new ReceiptItem('Название товара 2');
    $receipt->addItem($item2);
    $item2->setPrice(1456.21);
    $item2->setQuantity(2);
    $item2->setSum(2612.42);
    $item2->setTax(TaxSystem::VAT118);

    $payment = new Payment();
    $receipt->addPayment($payment);
    $payment->setSum(7612);
    $payment->setType(1);

    $service = new Service();
    $request->setService($service);
    $service->setCallbackUrl('http://example.com/callback');
    $service->setInn('331122667723');
    $service->setPaymentAddress('example.com');

    $this->assertEquals($expectedDate, $request->toArray());
  }

  public function testCatValidOperation() {
    $request = new Sell();

    $this->assertEquals('sell', $request->getOperation());
  }
}
