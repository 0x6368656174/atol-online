<?php
/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\AtolOnline;

use InvalidArgumentException;
use ItQuasar\AtolOnline\Exception\SdkException;

/**
 * Атрибуты налога.
 */
class Vat
{
  /** @var string */
  private $type = null;

  /** @var float|null */
  private $sum = null;

  /**
   * Возвращает сумму налога позиции в рублях.
   *
   * @return float|null
   */
  public function getSum(): ?float
  {
    return $this->sum;
  }

  /**
   * Устанавливает суммн налога позиции в рублях:
   * - целая часть не более 8 знаков;
   * - дробная часть не более 2 знаков.
   *
   * @param float|null $sum
   *
   * @return $this
   */
  public function setSum(?float $sum): self
  {
    if ($sum > 99999999) {
      throw new InvalidArgumentException('TaxSum too big. Max = 99999999');
    }

    $this->sum = $sum;

    return $this;
  }

  /**
   * Возвращает номер налога в ККТ.
   *
   * @return string
   */
  public function getType(): string
  {
    return $this->type;
  }

  /**
   * Устанавливает номер налога в ККТ.
   *
   * Перечисление со значениями:
   * - @see VatType::NONE – без НДС;
   * - @see VatType::VAT0 – НДС по ставке 0%;
   * - @see VatType::VAT10 – НДС чека по ставке 10%;
   * - @see VatType::VAT18 – НДС чека по ставке 18%;
   * - @see VatType::VAT20 – НДС чека по ставке 20%;
   * - @see VatType::VAT110 – НДС чека по расчетной ставке 10/110;
   * - @see VatType::VAT118 – НДС чека по расчетной ставке 18/118;
   * - @see VatType::VAT120 – НДС чека по расчетной ставке 20/120.
   *
   * @param string
   *
   * @return $this
   */
  public function setType(string $type): self
  {
    $this->type = $type;

    return $this;
  }

  public function toArray(): array
  {
    if (is_null($this->type)) {
      throw new SdkException('Type required');
    }

    $result = [
      'type' => $this->type,
    ];

    if (!is_null($this->sum)) {
      $result['sum'] = round($this->sum, 2);
    }

    return $result;
  }
}
