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
 * Атрибуты агента.
 */
class AgentInfo implements RequestPart
{
  /**
   * Возвращает признак агента.
   *
   * @return string|null
   */
  public function getType(): ?string
  {
    return $this->type;
  }

  /**
   * Устанавлиает признак агента (ограничен агентами, введенными в ККТ при фискализации).
   *
   * Возможные значения:
   * - @see AgentType::BANK_PAYING_AGENT – банковский платежный  агент. Оказание услуг покупателю (клиенту)
   *   пользователем, являющимся банковским платежным агентом банковским платежным агентом.
   * - @see AgentType::BANK_PAYING_SUBAGENT – банковский платежный субагент. Оказание услуг покупателю (клиенту)
   *   пользователем, являющимся банковским платежным агентом банковским платежным субагентом.
   * - @see AgentType::PAYING_AGENT – платежный агент. Оказание услуг покупателю (клиенту) пользователем, являющимся
   *   платежным агентом.
   * - @see AgentType::PAYING_SUBAGENT – платежный субагент. Оказание услуг покупателю (клиенту) пользователем,
   *   являющимся платежным субагентом.
   * - @see AgentType::ATTORNEY – поверенный. Осуществление расчета с покупателем (клиентом) пользователем,
   *   являющимся поверенным.
   * - @see AgentType::COMMISSION_AGENT – комиссионер. Осуществление расчета с покупателем (клиентом) пользователем,
   *   являющимся комиссионером.
   * - @see AgentType::ANOTHER – другой тип агента. Осуществление расчета с покупателем (клиентом) пользователем,
   *   являющимся агентом и не являющимся банковским платежным агентом (субагентом), платежным агентом (субагентом),
   *   поверенным, комиссионером.
   *
   * @param string|null $type
   *
   * @return $this
   */
  public function setType(?string $type): self
  {
    $this->type = $type;

    return $this;
  }

  /**
   * Возвращает атрибуты платежного агента.
   *
   * @return PayingAgent|null
   */
  public function getPayingAgent(): ?PayingAgent
  {
    return $this->payingAgent;
  }

  /**
   * Устанавлиает атрибуты платежного агента.
   *
   * @param PayingAgent|null $payingAgent
   *
   * @return $this
   */
  public function setPayingAgent(?PayingAgent $payingAgent): self
  {
    $this->payingAgent = $payingAgent;

    return $this;
  }

  /**
   * Возвращает атрибуты оператора по приему платежей.
   *
   * @return ReceivePaymentsOperator|null
   */
  public function getReceivePaymentsOperator(): ?ReceivePaymentsOperator
  {
    return $this->receivePaymentsOperator;
  }

  /**
   * Устанавлиает атрибуты оператора по приему платежей.
   *
   * @param ReceivePaymentsOperator|null $receivePaymentsOperator
   *
   * @return $this
   */
  public function setReceivePaymentsOperator(?ReceivePaymentsOperator $receivePaymentsOperator): self
  {
    $this->receivePaymentsOperator = $receivePaymentsOperator;

    return $this;
  }

  /**
   * Возвращает атрибуты оператора перевода.
   *
   * @return MoneyTransferOperator|null
   */
  public function getMoneyTransferOperator(): ?MoneyTransferOperator
  {
    return $this->moneyTransferOperator;
  }

  /**
   * Устанавливает атрибуты оператора перевода.
   *
   * @param MoneyTransferOperator|null $moneyTransferOperator
   *
   * @return $this
   */
  public function setMoneyTransferOperator(?MoneyTransferOperator $moneyTransferOperator): self
  {
    $this->moneyTransferOperator = $moneyTransferOperator;

    return $this;
  }
  /** @var string|null */
  private $type = null;

  /** @var null|PayingAgent  */
  private $payingAgent = null;

  /** @var null|ReceivePaymentsOperator */
  private $receivePaymentsOperator = null;

  /** @var null|MoneyTransferOperator */
  private $moneyTransferOperator = null;

  public function toArray(): array
  {
    $result = [];

    if (!is_null($this->type)) {
      $result['type'] = $this->type;
    }

    if (!is_null($this->payingAgent)) {
      $result['paying_agent'] = $this->payingAgent->toArray();
    }

    if (!is_null($this->receivePaymentsOperator)) {
      $result['receive_payments_operator'] = $this->receivePaymentsOperator->toArray();
    }

    if (!is_null($this->receivePaymentsOperator)) {
      $result['money_transfer_operator'] = $this->moneyTransferOperator->toArray();
    }

    return $result;
  }
}
