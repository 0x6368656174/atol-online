<?php

declare(strict_types=1);

/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ItQuasar\AtolOnline;

use DateTime;
use InvalidArgumentException;
use ItQuasar\AtolOnline\Exception\SdkException;

/**
 * Чек «Приход».
 */
class Sell implements Request
{
  /** @var string */
  private $externalId = null;

  /** @var Receipt */
  private $receipt = null;

  /** @var Service */
  private $service = null;

  /** @var DateTime */
  private $timestamp = null;

  /**
   * Возвращает идентификатор документа внешней системы.
   *
   * @return string
   */
  public function getExternalId(): string
  {
    return $this->externalId;
  }

  /**
   * Устанавливает идентификатор документа внешней системы.
   *
   * Уникальный среди всех документов, отправляемых в данную группу ККТ.
   *
   * Предназначен для защиты от потери документов при разрывах связи – всегда можно подать повторно чек с таким же
   * external_ID. Если данный external_id известен системе будет возвращен UUID, ранее присвоенный этому чеку,
   * иначе чек добавится в систему с присвоением нового UUID.
   *
   * Максимальная длина строки – 256 символов
   *
   * @param string $externalId
   *
   * @return Sell
   */
  public function setExternalId(string $externalId): self
  {
    if (strlen($externalId) > 256) {
      throw new InvalidArgumentException('ExternalId too big. Max length size = 256');
    }

    $this->externalId = $externalId;

    return $this;
  }

  /**
   * Возвращает чек.
   *
   * @return Receipt
   */
  public function getReceipt(): Receipt
  {
    return $this->receipt;
  }

  /**
   * Устанавливает чек.
   *
   * @param Receipt $receipt
   *
   * @return Sell
   */
  public function setReceipt(Receipt $receipt): self
  {
    $this->receipt = $receipt;

    return $this;
  }

  /**
   * Возвращете служебный раздел.
   *
   * @return Service
   */
  public function getService(): Service
  {
    return $this->service;
  }

  /**
   * Устанавливает служебный раздел.
   *
   * @param Service $service
   *
   * @return Sell
   */
  public function setService(Service $service): self
  {
    $this->service = $service;

    return $this;
  }

  /**
   * Возвращает дату и время документа внешней системы.
   *
   * @return DateTime
   */
  public function getTimestamp(): DateTime
  {
    return $this->timestamp;
  }

  /**
   * Устанавливает дату и время документа внешней системы.
   *
   * @param DateTime $timestamp
   *
   * @return Sell
   */
  public function setTimestamp(DateTime $timestamp): self
  {
    $this->timestamp = $timestamp;

    return $this;
  }

  public function toArray(): array
  {
    if (is_null($this->externalId)) {
      throw new SdkException('ExternalId required');
    }

    if (is_null($this->receipt)) {
      throw new SdkException('Receipt required');
    }

    if (is_null($this->service)) {
      throw new SdkException('Service required');
    }

    if (is_null($this->timestamp)) {
      throw new SdkException('Timestamp required');
    }

    return [
      'external_id' => $this->externalId,
      'receipt' => $this->receipt->toArray(),
      'service' => $this->service->toArray(),
      'timestamp' => $this->timestamp->format('d.m.Y H:i:s'),
    ];
  }

  public function getOperation(): string
  {
    return 'sell';
  }
}
