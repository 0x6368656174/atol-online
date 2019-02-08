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
 * Признак агента (ограничен агентами, введенными в ККТ при фискализации).
 */
class AgentType
{
  /**
   * @var string Банковский платежный агент. Оказание услуг покупателю (клиенту) пользователем, являющимся банковски
   * платежным агентом банковским платежным агентом.
   */
  const BANK_PAYING_AGENT = 'bank_paying_agent';

  /**
   * @var string Банковский платежный субагент. Оказание услуг покупателю (клиенту) пользователем, являющимся банковским
   * платежным агентом банковским платежным субагентом.
   */
  const BANK_PAYING_SUBAGENT = 'bank_paying_subagent';

  /** @var string Платежный агент. Оказание услуг покупателю (клиенту) пользователем, являющимся платежным агентом. */
  const PAYING_AGENT = 'paying_agent';

  /** @var string платежный субагент. Оказание услуг покупателю (клиенту) пользователем, являющимся платежным субагентом. */
  const PAYING_SUBAGENT = 'paying_subagent';

  /** @var string поверенный. Осуществление расчета с покупателем (клиентом) пользователем, являющимся поверенным. */
  const ATTORNEY = 'attorney';

  /** @var string комиссионер. Осуществление расчета с покупателем (клиентом) пользователем, являющимся комиссионером. */
  const COMMISSION_AGENT = 'commission_agent';

  /**
   * @var string другой тип агента. Осуществление расчета с покупателем (клиентом) пользователем, являющимся агентом и
   * не являющимся банковским платежным агентом (субагентом), платежным агентом (субагентом), поверенным, комиссионером.
   */
  const ANOTHER = 'another';
}
