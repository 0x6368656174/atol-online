<?php

declare(strict_types=1);

/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ItQuasar\AtolOnline;

use InvalidArgumentException;
use ItQuasar\AtolOnline\Exception\SdkException;
use function array_map;
use function count;

/**
 * Коррекция.
 */
class Correction implements RequestPart
{
  /** @var CorrectionAttributes */
  private $attributes = null;

  /** @var Payment[] */
  private $payments = [];

  /**
   * Возвращает атрибуты коррекции.
   *
   * @return CorrectionAttributes
   */
  public function getAttributes(): CorrectionAttributes
  {
    return $this->attributes;
  }

  /**
   * Устанавливает атрибуты коррекции.
   *
   * @param CorrectionAttributes $attributes
   */
  public function setAttributes(CorrectionAttributes $attributes): void
  {
    $this->attributes = $attributes;
  }

  /**
   * Возвращает оплату.
   *
   * @return Payment[]
   */
  public function getPayments(): array
  {
    return $this->payments;
  }

  /**
   * Устанавливает оплату.
   *
   * Ограничение по количеству от 1 до 10.
   *
   * @param Payment[] $payments
   */
  public function setPayments(array $payments): void
  {
    if (0 == count($payments) || count($payments) > 10) {
      throw new InvalidArgumentException('Payments count must be > 1 and < 10');
    }

    $this->payments = $payments;
  }

  /**
   * Добавляет оплату.
   *
   * Ограничение по количеству от 1 до 10.
   *
   * @param Payment $payment
   */
  public function addPayment(Payment $payment): void
  {
    if (10 == count($this->payments)) {
      throw new InvalidArgumentException('Payments full. Max payments count = 10');
    }

    $this->payments[] = $payment;
  }

  public function toArray(): array
  {
    if (is_null($this->attributes)) {
      throw new SdkException('Attributes required');
    }

    if (0 == count($this->payments)) {
      throw new SdkException('More then one payment required');
    }

    return [
      'attributes' => $this->attributes->toArray(),
      'payments' => array_map(function (Payment $payment) {
        return $payment->toArray();
      }, $this->payments),
    ];
  }
}
