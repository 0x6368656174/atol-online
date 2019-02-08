<?php
/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\AtolOnline;

use DateTime;

/**
 * Реквизиты фискализации документа.
 */
class ReportPayload implements ResponsePart
{
  /** @var int */
  private $fiscalReceiptNumber;

  /** @var int */
  private $shiftNumber;

  /** @var DateTime */
  private $receiptDateTime;

  /** @var float */
  private $total;

  /** @var string */
  private $fnNumber;

  /** @var string */
  private $ecrRegistrationNumber;

  /** @var int */
  private $fiscalDocumentNumber;

  /** @var int */
  private $fiscalDocumentAttribute;

  /** @var string */
  private $fnsSite;

  /**
   * Возвращает номер чека в смене.
   *
   * @return int
   */
  public function getFiscalReceiptNumber(): int
  {
    return $this->fiscalReceiptNumber;
  }

  /**
   * Возвращает номер смены.
   *
   * @return int
   */
  public function getShiftNumber(): int
  {
    return $this->shiftNumber;
  }

  /**
   * Возвращает дату и время документа из ФН.
   *
   * @return DateTime
   */
  public function getReceiptDateTime(): DateTime
  {
    return $this->receiptDateTime;
  }

  /**
   * Возвращает итоговую сумму документа в рублях.
   *
   * При регистрации в ККТ происходит расчёт фактической суммы: суммирование значений sum позиций
   *
   * @return float
   */
  public function getTotal(): float
  {
    return $this->total;
  }

  /**
   * Возвращает номер ФН.
   *
   * @return string
   */
  public function getFnNumber(): string
  {
    return $this->fnNumber;
  }

  /**
   * Возвращает регистрационный номер ККТ.
   *
   * @return string
   */
  public function getEcrRegistrationNumber(): string
  {
    return $this->ecrRegistrationNumber;
  }

  /**
   * Возвращает фискальный номер документа.
   *
   * @return int
   */
  public function getFiscalDocumentNumber(): int
  {
    return $this->fiscalDocumentNumber;
  }

  /**
   * Возвращает фискальный признак документа.
   *
   * @return int
   */
  public function getFiscalDocumentAttribute(): int
  {
    return $this->fiscalDocumentAttribute;
  }

  /**
   * Возвращает адрес сайта ФНС
   *
   * @return string
   */
  public function getFnsSite(): string
  {
    return $this->fnsSite;
  }

  public static function fromArray(array $array): ReportPayload
  {
    $result = new ReportPayload();

    $result->fiscalReceiptNumber = $array['fiscal_receipt_number'];
    $result->shiftNumber = $array['shift_number'];
    $result->receiptDateTime = DateTime::createFromFormat('d.m.Y H:i:s', $array['receipt_datetime']);
    $result->total = $array['total'];
    $result->fnNumber = $array['fn_number'];
    $result->ecrRegistrationNumber = $array['ecr_registration_number'];
    $result->fiscalDocumentNumber = $array['fiscal_document_number'];
    $result->fiscalDocumentAttribute = $array['fiscal_document_attribute'];
    $result->fnsSite = $array['fns_site'];

    return $result;
  }
}
