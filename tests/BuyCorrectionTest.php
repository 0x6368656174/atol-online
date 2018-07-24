<?php

declare(strict_types=1);

use ItQuasar\AtolOnline\BuyCorrection;
use ItQuasar\AtolOnline\Correction;
use ItQuasar\AtolOnline\CorrectionAttributes;
use ItQuasar\AtolOnline\Payment;
use ItQuasar\AtolOnline\Service;
use ItQuasar\AtolOnline\SnoSystem;
use ItQuasar\AtolOnline\TaxSystem;
use PHPUnit\Framework\TestCase;

/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
final class BuyCorrectionTest extends TestCase
{
  public function testCanReturnValidArray()
  {
    $dir = __DIR__;
    $expectedDateJson = file_get_contents("$dir/buy-correction-request.json");
    $expectedDate = json_decode($expectedDateJson, true);

    $request = new BuyCorrection();
    $request->setExternalId('17052917561851307');

    $timestamp = new DateTime();
    $timestamp->setDate(2017, 05, 29);
    $timestamp->setTime(17, 56, 18);
    $request->setTimestamp($timestamp);

    $service = new Service();
    $request->setService($service);

    $service->setCallbackUrl('http://example.com/callback');
    $service->setInn('331122667723');
    $service->setPaymentAddress('example.com');

    $correction = new Correction();
    $request->setCorrection($correction);

    $attributes = new CorrectionAttributes();
    $correction->setAttributes($attributes);
    $attributes->setSno(SnoSystem::OSN);
    $attributes->setTax(TaxSystem::VAT18);

    $payment = new Payment();
    $correction->addPayment($payment);
    $payment->setSum(7612);
    $payment->setType(1);

    $this->assertEquals($expectedDate, $request->toArray());
  }

  public function testCatValidOperation()
  {
    $request = new BuyCorrection();

    $this->assertEquals('buy_correction', $request->getOperation());
  }
}
