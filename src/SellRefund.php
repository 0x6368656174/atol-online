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
 * Чек «Возврат прихода»
 */
class SellRefund extends Sell implements Request
{
  public function getOperation(): string
  {
    return 'sell_refund';
  }
}
