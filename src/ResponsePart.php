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
 * Часть ответа
 */
interface ResponsePart
{
  /**
   * Создает из данных, полученных из АТОЛ Онлайн.
   *
   * @param array $array Данные из АТОЛ Онлайн
   *
   * @return mixed
   */
  public static function fromArray(array $array);
}
