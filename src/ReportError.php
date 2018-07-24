<?php

declare(strict_types=1);

/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ItQuasar\AtolOnline;

/**
 * Описание ошибки.
 */
class ReportError implements ResponsePart
{
  /**
   * Системная ошибка.
   */
  public const TYPE_SYSTEM = 'system';

  /**
   * Ошибка при работе с ККТ.
   */
  public const TYPE_DRIVER = 'driver';

  /**
   * Превышено время ожидания. Время ожидания задается в системе. На данный момент установлено 300 сек.
   */
  public const TYPE_TIMEOUT = 'timeout';

  /**
   * @var int
   */
  private $code;

  /**
   * @var string
   */
  private $text;

  /**
   * @var string
   */
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
   * - ReportError::TYPE_SYSTEM – системная ошибка;
   * - ReportError::TYPE_DRIVER – ошибка при работе с ККТ;
   * - ReportError::TYPE_TIMEOUT – превышено время ожидания. Время ожидания задается в системе.
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
