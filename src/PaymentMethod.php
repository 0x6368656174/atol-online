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
 * Признак способа расчета
 */
class PaymentMethod
{
  /** @var string Предоплата 100%. Полная предварительная оплата до момента передачи предмета расчета */
  const FULL_PREPAYMENT = 'full_prepayment';

  /** @var string  Предоплата. Частичная предварительная оплата до момента передачи предмета расчета */
  const PREPAYMENT = 'prepayment';

  /** @var string Аванс */
  const ADVANCE = 'advance';

  /**
   * @var string Полный расчет. Полная оплата, в том числе с учетом аванса (предварительной оплаты) в момент передачи
   *  предмета расчета.
   */
  const FULL_PAYMENT = 'full_payment';

  /**
   * @var string Частичный расчет и кредит. Частичная оплата предмета расчета в момент его передачи с
   * последующей оплатой в кредит
   */
  const PARTIAL_PAYMENT = 'partial_payment';

  /**
   * @var string Передача в кредит. Передача предмета расчета без его оплаты в момент его передачи с последующей
   * оплатой в кредит.
   */
  const CREDIT = 'credit';

  /** @var string Оплата кредита. Оплата предмета расчета после его передачи с оплатой в кредит (оплата кредита). */
  const CREDIT_PAYMENT = 'credit_payment';
}
