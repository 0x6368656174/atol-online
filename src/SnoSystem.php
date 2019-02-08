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
 * Система налогообложения.
 */
class SnoSystem
{
  /** @var string Общая СН. */
  public const OSN = 'osn';

  /** @var string Упрощенная СН (доходы). */
  public const USN_INCOME = 'usn_income';

  /** @var string Упрощенная СН (доходы минус расходы). */
  public const USN_INCOME_OUTCOME = 'usn_income_outcome';

  /** @var string Единый налог на вмененный доход. */
  public const ENVD = 'envd';

  /** @var string Единый сельскохозяйственный налог. */
  public const ESN = 'esn';

  /** @var string Патентная СН. */
  public const PATENT = 'patent';
}
