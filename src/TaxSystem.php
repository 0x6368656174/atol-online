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
 * Номер налога в ККТ.
 */
class TaxSystem
{
  /**
   * Без НДС
   */
  public const NONE = 'none';

  /**
   * НДС чека по ставке 0%.
   */
  public const VAT0 = 'vat0';

  /**
   * НДС чека по ставке 10%.
   */
  public const VAT10 = 'vat10';

  /**
   * НДС чека по ставке 18%.
   */
  public const VAT18 = 'vat18';

  /**
   * НДС чека по расчетной ставке 10/110.
   */
  public const VAT110 = 'vat110';

  /**
   * НДС чека по расчетной ставке 18/118.
   */
  public const VAT118 = 'vat118';
}
