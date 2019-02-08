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
use function array_map;
use function count;

/**
 * Коррекция.
 */
class Correction implements RequestPart
{
  /** @var Payment[] */
  private $payments = [];

  /** @var null|CorrectionCompany */
  private $company = null;

  /** @var null|CorrectionInfo */
  private $correctionInfo = null;

  /** @var Vat[]  */
  private $vats = [];

  /** @var null|string */
  private $cashier = null;

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
   *
   * @return $this
   */
  public function setPayments(array $payments): self
  {
    if (0 == count($payments) || count($payments) > 10) {
      throw new InvalidArgumentException('Payments count must be > 1 and < 10');
    }

    $this->payments = $payments;

    return $this;
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

  /**
   * Возвращает атрибуты компании.
   *
   * @return CorrectionCompany
   */
  public function getCompany(): CorrectionCompany
  {
    return $this->company;
  }

  /**
   * Устанавливает атрибуты компании.
   *
   * @param CorrectionCompany $company
   *
   * @return $this
   */
  public function setCompany(CorrectionCompany $company): self
  {
    $this->company = $company;

    return $this;
  }

  /**
   * Возвращает коррекцию.
   *
   * @return CorrectionInfo
   */
  public function getCorrectionInfo(): CorrectionInfo
  {
    return $this->correctionInfo;
  }

  /**
   * Устанавлиает коррекцию.
   *
   * @param CorrectionInfo $correctionInfo
   *
   * @return $this
   */
  public function setCorrectionInfo(CorrectionInfo $correctionInfo): self
  {
    $this->correctionInfo = $correctionInfo;

    return $this;
  }

  /**
   * Возвращает атрибуты налогов на чек коррекции.
   *
   * @return Vat[]
   */
  public function getVats(): array {
    return $this->vats;
  }

  /**
   * Устанавлиает атрибуты налога на чек коррекции.
   *
   * Ограничение по количеству от 1 до 6.
   *
   * Необходимо передать либо сумму налога на позицию, либо сумму налога на чек. Если будет переданы и сумма налога
   * на позицию и сумма налога на чек, сервис учтет только сумму налога на чек.
   *
   * @param array $vats
   *
   * @return $this
   */
  public function setVats(array $vats): self {
    if (0 === count($vats) || count($vats) > 6) {
      throw new InvalidArgumentException('Vats count must be less then 7');
    }

    $this->vats = $vats;

    return $this;
  }

  /**
   * Добавляет атрибут налога на чек коррекции.
   *
   * Ограничение по количеству от 1 до 6.
   *
   * Необходимо передать либо сумму налога на позицию, либо сумму налога на чек. Если будет переданы и сумма налога
   * на позицию и сумма налога на чек, сервис учтет только сумму налога на чек.
   *
   * @param Vat $vat
   *
   * @return $this
   */
  public function addVat(Vat $vat): self {
    if (count($this->vats) === 6) {
      throw new InvalidArgumentException('Vats full. Max payments count = 6');
    }

    $this->vats[] = $vat;

    return $this;
  }

  /**
   * Возвращает ФИО кассира.
   *
   * @return string|null
   */
  public function getCashier(): ?string  {
    return $this->cashier;
  }

  /**
   * Устанавливает ФИО кассира.
   *
   * Максимальная длина строки – 64 символа.
   *
   * @param string|null $cashier
   *
   * @return $this
   */
  public function setCashier(?string $cashier): self {
    if (mb_strlen($cashier) > 64) {
      throw new InvalidArgumentException('Cashier too big. Max length size = 64');
    }

    $this->cashier = $cashier;

    return $this;
  }

  public function toArray(): array
  {
    if (is_null($this->company)) {
      throw new SdkException('Company required');
    }

    if (0 == count($this->payments)) {
      throw new SdkException('More then one payment required');
    }

    if (0 == count($this->vats)) {
      throw new SdkException('More then one vat required');
    }

    if (is_null($this->correctionInfo)) {
      throw new SdkException('Correction info required');
    }

    $result = [
      'company' => $this->company->toArray(),
      'payments' => array_map(function (Payment $payment) {
        return $payment->toArray();
      }, $this->payments),
      'correction_info' => $this->correctionInfo->toArray(),
      'vats' => array_map(function (Vat $vat) {
        return $vat->toArray();
      }, $this->vats),
    ];

    if (!is_null($this->cashier)) {
      $result['cashier'] = $this->cashier;
    }

    return $result;
  }
}
