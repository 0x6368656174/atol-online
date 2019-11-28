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
 * Часть запроса
 */
interface RequestPart
{
  /**
   * Возвращает массив, готовый для отправки в АТОЛ Онлайн.
   *
   * @return array
   */
  public function toArray(): array;
}
