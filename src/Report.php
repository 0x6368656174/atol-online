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
use function is_null;

/**
 * Статуса обработки документа.
 */
class Report implements ResponsePart
{
  /** Статус "готово". */
  public const STATUS_DONE = 'done';

  /** Статус "ошибка". */
  public const STATUS_FAIL = 'fail';

  /** Статус "ожидание". */
  public const STATUS_WAIT = 'wait';

  /** @var string */
  private $uuid;

  /** @var DateTime */
  private $timestamp;

  /** @var string */
  private $callbackUrl;

  /** @var string */
  private $status;

  /** @var string */
  private $groupCode;

  /** @var string */
  private $daemonCode;

  /** @var string */
  private $deviceCode;

  /** @var ReportError|null */
  private $error;

  /** @var ReportPayload|null */
  private $payload;

  /**
   * Возвращает уникальный идентификатор
   *
   * @return string
   */
  public function getUuid(): string
  {
    return $this->uuid;
  }

  /**
   * Возвращает дата и время документа внешней системы.
   *
   * @return DateTime
   */
  public function getTimestamp(): DateTime
  {
    return $this->timestamp;
  }

  /**
   * Возвращает URL, на который необходимо ответить после обработки документа.
   *
   * @return string
   */
  public function getCallbackUrl(): string
  {
    return $this->callbackUrl;
  }

  /**
   * Возвращает статус.
   *
   * Возможные значения:
   * - Report::STATUS_DONE – готово;
   * - Report::STATUS_FAIL – ошибка;
   * - Report::STATUS_WAIT – ожидание
   *
   * @return string
   */
  public function getStatus(): string
  {
    return $this->status;
  }

  /**
   * Возвращает идентификатор группы ККТ.
   *
   * @return string
   */
  public function getGroupCode(): string
  {
    return $this->groupCode;
  }

  /**
   * Возвращает наименование сервера.
   *
   * @return string
   */
  public function getDaemonCode(): string
  {
    return $this->daemonCode;
  }

  /**
   * Возвращает код ККТ.
   *
   * @return string
   */
  public function getDeviceCode(): string
  {
    return $this->deviceCode;
  }

  /**
   * Возвращает описание ошибки.
   *
   * @return ReportError|null
   */
  public function getError(): ?ReportError
  {
    return $this->error;
  }

  /**
   * Возвращает реквизиты фискализации документа.
   *
   * @return ReportPayload|null
   */
  public function getPayload(): ?ReportPayload
  {
    return $this->payload;
  }

  public static function fromArray(array $array): Report
  {
    $result = new Report();

    $result->uuid = $array['uuid'];
    $result->timestamp = DateTime::createFromFormat('d.m.Y H:i:s', $array['timestamp']);
    $result->callbackUrl = $array['callback_url'];
    $result->status = $array['status'];
    $result->groupCode = $array['group_code'];
    $result->daemonCode = $array['daemon_code'];
    $result->deviceCode = $array['device_code'];

    if (is_null($array['error'])) {
      $result->error = null;
    } else {
      $result->error = ReportError::fromArray($array['error']);
    }

    if (is_null($array['payload'])) {
      $result->payload = null;
    } else {
      $result->payload = ReportPayload::fromArray($array['payload']);
    }

    return $result;
  }
}
