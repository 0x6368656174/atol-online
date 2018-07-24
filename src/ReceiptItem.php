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
use function is_null;

/**
 * Позиция чека.
 */
class ReceiptItem implements RequestPart
{
  /** @var string */
  private $name = null;

  /** @var float */
  private $price = null;

  /** @var float; */
  private $quantity = null;

  /** @var float */
  private $sum = null;

  /** @var string */
  private $tax = null;

  /** @var float|null */
  private $taxSum = null;

  /**
   * Создает позицию чека.
   *
   * @param null|string $name Название товара
   */
  public function __construct(?string $name = null)
  {
    $this->setName($name);
  }

  /**
   * Возвращает сумму налога позиции в рублях.
   *
   * @return float|null
   */
  public function getTaxSum(): ?float
  {
    return $this->taxSum;
  }

  /**
   * Устанавливает суммн налога позиции в рублях:
   * - целая часть не более 8 знаков;
   * - дробная часть не более 2 знаков.
   *
   * @param float|null $taxSum
   *
   * @return ReceiptItem
   */
  public function setTaxSum(?float $taxSum): self
  {
    if ($taxSum > 99999999) {
      throw new InvalidArgumentException('TaxSum too big. Max = 99999999');
    }

    $this->taxSum = $taxSum;

    return $this;
  }

  /**
   * Возвращает наименование товара.
   *
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Устанавливает наименование товара. Максимальная длина строки – 64 символа.
   *
   * @param string $name
   *
   * @return ReceiptItem
   */
  public function setName(string $name): self
  {
    if (strlen($name) > 64) {
      throw new InvalidArgumentException('Name too big. Max length size = 64');
    }

    $this->name = $name;

    return $this;
  }

  /**
   * Возвращает цену в рублях.
   *
   * @return float
   */
  public function getPrice(): float
  {
    return $this->price;
  }

  /**
   * Устанавлиает цену в рублях:
   * - целая часть не более 8 знаков;
   * - дробная часть не более 2 знаков.
   *
   * @param float $price
   *
   * @return ReceiptItem
   */
  public function setPrice(float $price): self
  {
    if ($price > 99999999) {
      throw new InvalidArgumentException('Price too big. Max = 99999999');
    }

    $this->price = $price;

    return $this;
  }

  /**
   * Возвращает количество/вес
   *
   * @return float
   */
  public function getQuantity(): float
  {
    return $this->quantity;
  }

  /**
   * Устанавливает количество/вес:
   * - целая часть не более 8 знаков;
   * - дробная часть не более 3 знаков.
   *
   * @param float $quantity
   *
   * @return ReceiptItem
   */
  public function setQuantity(float $quantity): self
  {
    if ($quantity > 99999999) {
      throw new InvalidArgumentException('Quantity too big. Max = 99999999');
    }

    $this->quantity = $quantity;

    return $this;
  }

  /**
   * Возвращает сумму позиции в рублях.
   *
   * @return float
   */
  public function getSum(): float
  {
    return $this->sum;
  }

  /**
   * Устанавливает сумму позиции в рублях:
   * - целая часть не более 8 знаков;
   * - дробная часть не более 2 знаков.
   *
   * Если значение sum меньше/больше значения (price*quantity), то разница является скидкой/надбавкой на позицию
   * соответственно. В этих случаях происходит перерасчёт поля price для равномерного распределения
   * скидки/надбавки по позициям.
   *
   * @param float $sum
   *
   * @return ReceiptItem
   */
  public function setSum(float $sum): self
  {
    if ($sum > 99999999) {
      throw new InvalidArgumentException('Sum too big. Max = 99999999');
    }

    $this->sum = $sum;

    return $this;
  }

  /**
   * Возвращает номер налога в ККТ.
   *
   * @return string
   */
  public function getTax(): string
  {
    return $this->tax;
  }

  /**
   * Устанавливает номер налога в ККТ.
   *
   * Перечисление со значениями:
   * - TaxSystem::NONE – без НДС;
   * - TaxSystem::VAT0 – НДС по ставке 0%;
   * - TaxSystem::VAT10 – НДС чека по ставке 10%;
   * - TaxSystem::VAT18 – НДС чека по ставке 18%;
   * - TaxSystem::VAT110 – НДС чека по расчетной ставке 10/110;
   * - TaxSystem::VAT118 – НДС чека по расчетной ставке 18/118.
   *
   * @param string
   *
   * @return ReceiptItem
   */
  public function setTax(string $tax): self
  {
    $this->tax = $tax;

    return $this;
  }

  public function toArray(): array
  {
    if (is_null($this->name)) {
      throw new SdkException('Name required');
    }

    if (is_null($this->price)) {
      throw new SdkException('Price required');
    }

    if (is_null($this->sum)) {
      throw new SdkException('Sum required');
    }

    if (is_null($this->tax)) {
      throw new SdkException('Tax required');
    }

    $result = [
      'name' => $this->name,
      'price' => round($this->price, 2),
      'quantity' => round($this->quantity, 3),
      'sum' => round($this->sum, 2),
      'tax' => $this->tax,
    ];

    if (!is_null($this->taxSum)) {
      $result['tax_sum'] = round($this->taxSum, 2);
    }

    return $result;
  }
}
