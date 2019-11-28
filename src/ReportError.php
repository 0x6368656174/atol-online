<?php
/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\AtolOnline;

/**
 * Описание ошибки.
 */
class ReportError implements ResponsePart
{
  /** @var string Системная ошибка. */
  public const TYPE_SYSTEM = 'system';

  /** @var string Ошибка при работе с ККТ. */
  public const TYPE_DRIVER = 'driver';

  /** @var string Превышено время ожидания. Время ожидания задается в системе. На данный момент установлено 300 сек. */
  public const TYPE_TIMEOUT = 'timeout';

  /** @var int */
  private $code;

  /** @var string */
  private $text;

  /** @var string */
  private $type;

  /**
   * Возвращает код ошибки.
   *
   * @return int
   */
  public function getCode(): int
  {
    return $this->code;
  }

  /**
   * Возвращает текст ошибки.
   *
   * @return string
   */
  public function getText(): string
  {
    return $this->text;
  }

  /**
   * Возваращает тип источника ошибки.
   *
   * Возможные значения:
   * - @see ReportError::TYPE_SYSTEM – системная ошибка;
   * - @see ReportError::TYPE_DRIVER – ошибка при работе с ККТ;
   * - @see ReportError::TYPE_TIMEOUT – превышено время ожидания. Время ожидания задается в системе.
   *   На данный момент установлено 300 сек.
   *
   * @return string
   */
  public function getType(): string
  {
    return $this->type;
  }

  public static function fromArray(array $array): ReportError
  {
    $result = new ReportError();

    $result->code = $array['code'];
    $result->text = $array['text'];
    $result->type = $array['type'];

    return $result;
  }
}
