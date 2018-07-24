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

/**
 * Оплата.
 */
class Payment implements RequestPart
{
  /**
   * @var int
   */
  private $type = 1;

  /**
   * @var float
   */
  private $sum = null;

  /**
   * Возвращает вид оплаты.
   *
   * @return int
   */
  public function getType(): int
  {
    return $this->type;
  }

  /**
   * Устанавливает вид оплаты.
   *
   * Возможные значения:
   * - 1 – электронный;
   * - 2 – 9 – расширенные типы оплаты. Для каждого фискального типа оплаты можно указать расширенный тип оплаты.
   *
   * @param int $type
   */
  public function setType(int $type): void
  {
    $this->type = $type;
  }

  /**
   * Возвращает сумму к оплате в рублях.
   *
   * @return float
   */
  public function getSum(): float
  {
    return $this->sum;
  }

  /**
   * Устанавливает сумму к оплате в рублях:
   * - целая часть не более 8 знаков;
   * - дробная часть не более 2 знаков.
   *
   * @param float $sum
   */
  public function setSum(float $sum): void
  {
    if ($sum > 99999999) {
      throw new InvalidArgumentException('Sum too big. Max = 99999999');
    }

    $this->sum = $sum;
  }

  public function toArray(): array
  {
    if (is_null($this->type)) {
      throw new SdkException('Type required');
    }

    if (is_null($this->sum)) {
      throw new SdkException('Sum required');
    }

    return [
      'type' => $this->type,
      'sum' => round($this->sum, 2),
    ];
  }
}
