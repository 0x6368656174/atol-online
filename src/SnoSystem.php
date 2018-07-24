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
 * Система налогообложения.
 */
class SnoSystem
{
  /**
   * Общая СН.
   */
  public const OSN = 'osn';

  /**
   * Упрощенная СН (доходы).
   */
  public const USN_INCOME = 'usn_income';

  /**
   * Упрощенная СН (доходы минус расходы).
   */
  public const USN_INCOME_OUTCOME = 'usn_income_outcome';

  /**
   * Единый налог на вмененный доход.
   */
  public const ENVD = 'envd';

  /**
   * Единый сельскохозяйственный налог.
   */
  public const ESN = 'esn';

  /**
   * Патентная СН.
   */
  public const PATENT = 'patent';
}
