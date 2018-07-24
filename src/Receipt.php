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
use function round;

/**
 * Чек.
 */
class Receipt implements RequestPart
{
  /** @var ReceiptAttributes */
  private $attributes = null;

  /** @var ReceiptItem[] */
  private $items = [];

  /** @var Payment[] */
  private $payments = [];

  /** @var float */
  private $total = null;

  /**
   * Возвращает итоговую сумму чека в рублях.
   *
   * @return float
   */
  public function getTotal(): float
  {
    return $this->total;
  }

  /**
   * Устанавливает итоговую сумму чека в рублях:
   * - целая часть не более 8 знаков;
   * - дробная часть не более 2 знаков.
   *
   * @param float $total
   */
  public function setTotal(float $total): void
  {
    if ($total > 99999999) {
      throw new InvalidArgumentException('Total too big. Max = 99999999');
    }

    $this->total = $total;
  }

  /**
   * Возвращает атрибуты чека.
   *
   * @return ReceiptAttributes
   */
  public function getAttributes(): ReceiptAttributes
  {
    return $this->attributes;
  }

  /**
   * Устанавливает атрибуты чека.
   *
   * @param ReceiptAttributes $attributes
   */
  public function setAttributes(ReceiptAttributes $attributes): void
  {
    $this->attributes = $attributes;
  }

  /**
   * Возвращает позиции чека.
   *
   * @return ReceiptItem[]
   */
  public function getItems(): array
  {
    return $this->items;
  }

  /**
   * Устанавливает позиции чека.
   *
   * Ограничение по количеству от 1 до 100.
   *
   * @param ReceiptItem[] $items
   */
  public function setItems(array $items): void
  {
    if (0 == count($items) || count($items) > 100) {
      throw new InvalidArgumentException('Items count must be > 1 and < 100');
    }

    $this->items = $items;
  }

  /**
   * Добавляет позицию чека.
   *
   * Ограничение по количеству от 1 до 100.
   *
   * @param ReceiptItem $item
   */
  public function addItem(ReceiptItem $item): void
  {
    if (100 == count($this->items)) {
      throw new InvalidArgumentException('Items full. Max items count = 100');
    }

    $this->items[] = $item;
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

    if (is_null($this->total)) {
      throw new SdkException('Total required');
    }

    if (0 == count($this->items)) {
      throw new SdkException('More then one item required');
    }

    if (0 == count($this->payments)) {
      throw new SdkException('More then one payment required');
    }

    return [
      'attributes' => $this->attributes->toArray(),
      'items' => array_map(function (ReceiptItem $item) {
        return $item->toArray();
      }, $this->items),
      'payments' => array_map(function (Payment $payment) {
        return $payment->toArray();
      }, $this->payments),
      'total' => round($this->total, 2),
    ];
  }
}
