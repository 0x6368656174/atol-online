<?php
/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\AtolOnline;

use InvalidArgumentException;
use function is_null;
use ItQuasar\AtolOnline\Exception\SdkException;
use function array_map;
use function count;
use function round;

/**
 * Чек.
 */
class Receipt implements RequestPart
{
  /** @var null|Client */
  private $client = null;

  /** @var null|Company */
  private $company = null;

  /** @var Item[] */
  private $items = [];

  /** @var Payment[] */
  private $payments = [];

  /** @var float */
  private $total = null;

  /** @var Vat[]  */
  private $vats = [];

  /** @var null|AgentInfo */
  private $agentInfo = null;

  /** @var null|SupplierInfo */
  private $supplierInfo = null;

  /** @var null|string */
  private $cashier = null;

  /** @var null|AdditionalUserProps */
  private $additionalUserProps = null;

  /**
   * Возвращает итоговую сумму чека в рублях.
   *
   * @return float
   */
  public function getTotal(): float
  {
    return $this->total;
  }

  /**
   * Устанавливает итоговую сумму чека в рублях:
   * - целая часть не более 8 знаков;
   * - дробная часть не более 2 знаков.
   *
   * @param float $total
   *
   * @return $this
   */
  public function setTotal(float $total): self
  {
    if ($total > 99999999) {
      throw new InvalidArgumentException('Total too big. Max = 99999999');
    }

    $this->total = $total;

    return $this;
  }

  /**
   * Возвращает атрибуты клиента.
   *
   * @return Client
   */
  public function getClient(): Client
  {
    return $this->client;
  }

  /**
   * Устанавливает атрибуты клиента.
   *
   * @param Client $client
   *
   * @return $this
   */
  public function setClient(Client $client): self
  {
    $this->client = $client;

    return $this;
  }

  /**
   * Возвращает атрибуты компании.
   *
   * @return Company
   */
  public function getCompany(): Company
  {
    return $this->company;
  }

  /**
   * Устанавливает атрибуты компании.
   *
   * @param Company $company
   *
   * @return $this
   */
  public function setCompany(Company $company): self
  {
    $this->company = $company;

    return $this;
  }

  /**
   * Возвращает позиции чека.
   *
   * @return Item[]
   */
  public function getItems(): array
  {
    return $this->items;
  }

  /**
   * Устанавливает позиции чека.
   *
   * Ограничение по количеству от 1 до 100.
   *
   * @param Item[] $items
   *
   * @return $this
   */
  public function setItems(array $items): self
  {
    if (0 == count($items) || count($items) > 100) {
      throw new InvalidArgumentException('Items count must be > 1 and < 100');
    }

    $this->items = $items;

    return $this;
  }

  /**
   * Добавляет позицию чека.
   *
   * Ограничение по количеству от 1 до 100.
   *
   * @param Item $item
   */
  public function addItem(Item $item): void
  {
    if (100 == count($this->items)) {
      throw new InvalidArgumentException('Items full. Max items count = 100');
    }

    $this->items[] = $item;
  }

  /**
   * Возвращает оплату.
   *
   * @return Payment[]
   */
  public function getPayments(): array
  {
    return $this->payments;
  }

  /**
   * Устанавливает оплату.
   *
   * Ограничение по количеству от 1 до 10.
   *
   * @param Payment[] $payments
   *
   * @return $this
   */
  public function setPayments(array $payments): self
  {
    if (0 === count($payments) || count($payments) > 10) {
      throw new InvalidArgumentException('Payments count must be >= 1 and <= 10');
    }

    $this->payments = $payments;

    return $this;
  }

  /**
   * Добавляет оплату.
   *
   * Ограничение по количеству от 1 до 10.
   *
   * @param Payment $payment
   *
   * @return $this
   */
  public function addPayment(Payment $payment): self
  {
    if (10 === count($this->payments)) {
      throw new InvalidArgumentException('Payments full. Max payments count = 10');
    }

    $this->payments[] = $payment;

    return $this;
  }

  /**
   * Возвращает атрибуты налогов на чек.
   *
   * @return Vat[]
   */
  public function getVats(): array {
    return $this->vats;
  }

  /**
   * Устанавлиает атрибуты налога на чек.
   *
   * Ограничение по количеству от 1 до 6.
   *
   * Необходимо передать либо сумму налога на позицию, либо сумму налога на чек. Если будет переданы и сумма налога
   * на позицию и сумма налога на чек, сервис учтет только сумму налога на чек.
   *
   * @param array $vats
   *
   * @return $this
   */
  public function setVats(array $vats): self {
    if (0 === count($vats) || count($vats) > 6) {
      throw new InvalidArgumentException('Vats count must be less then 7');
    }

    $this->vats = $vats;

    return $this;
  }

  /**
   * Добавляет атрибут налога на чек.
   *
   * Ограничение по количеству от 1 до 6.
   *
   * Необходимо передать либо сумму налога на позицию, либо сумму налога на чек. Если будет переданы и сумма налога
   * на позицию и сумма налога на чек, сервис учтет только сумму налога на чек.
   *
   * @param Vat $vat
   *
   * @return $this
   */
  public function addVat(Vat $vat): self {
    if (count($this->vats) === 6) {
      throw new InvalidArgumentException('Vats full. Max payments count = 6');
    }

    $this->vats[] = $vat;

    return $this;
  }

  /**
   * Возвращает атрибуты агента.
   *
   * @return AgentInfo|null
   */
  public function getAgentInfo(): ?AgentInfo {
    return $this->agentInfo;
  }

  /**
   * Устанавливает атрибуты агента.
   *
   * @param AgentInfo|null $agentInfo
   *
   * @return $this
   */
  public function setAgentInfo(?AgentInfo $agentInfo): self {
    $this->agentInfo = $agentInfo;

    return $this;
  }

  /**
   * Возвращает атрибуты поставщика.
   *
   * @return SupplierInfo|null
   */
  public function getSupplierInfo(): ?SupplierInfo {
    return $this->supplierInfo;
  }

  /**
   * Устанавливает атрибуты поставщика.
   *
   * @param SupplierInfo|null $supplierInfo
   *
   * @return $this
   */
  public function setSupplierInfo(?SupplierInfo $supplierInfo): self {
    $this->supplierInfo = $supplierInfo;

    return $this;
  }

  /**
   * Возвращает ФИО кассира.
   *
   * @return string|null
   */
  public function getCashier(): ?string  {
    return $this->cashier;
  }

  /**
   * Устанавливает ФИО кассира.
   *
   * Максимальная длина строки – 64 символа.
   *
   * @param string|null $cashier
   *
   * @return $this
   */
  public function setCashier(?string $cashier): self {
    if (mb_strlen($cashier) > 64) {
      throw new InvalidArgumentException('Cashier too big. Max length size = 64');
    }

    $this->cashier = $cashier;

    return $this;
  }

  /**
   * Возвращает дополнительный реквизит пользователя.
   *
   * @return AdditionalUserProps|null
   */
  public function getAdditionalUserProps(): ?AdditionalUserProps
  {
    return $this->additionalUserProps;
  }

  /**
   * Устанавлиает дополнительный реквизит пользователя.
   *
   * @param AdditionalUserProps|null $additionalUserProps
   *
   * @return $this
   */
  public function setAdditionalUserProps(?AdditionalUserProps $additionalUserProps): self
  {
    $this->additionalUserProps = $additionalUserProps;

    return $this;
  }

  public function toArray(): array
  {
    if (is_null($this->client)) {
      throw new SdkException('Client required');
    }

    if (is_null($this->company)) {
      throw new SdkException('Company required');
    }

    if (is_null($this->total)) {
      throw new SdkException('Total required');
    }

    if (0 == count($this->items)) {
      throw new SdkException('More then one item required');
    }

    if (0 == count($this->payments)) {
      throw new SdkException('More then one payment required');
    }

    if (!is_null($this->agentInfo) && is_null($this->supplierInfo)) {
      throw new SdkException('Supplier info required if agent info sets.');
    }

    if (!is_null($this->supplierInfo) && is_null($this->agentInfo)) {
      throw new SdkException('Agent info required if supplier info sets.');
    }

    $result = [
      'client' => $this->client->toArray(),
      'company' => $this->company->toArray(),
      'items' => array_map(function (Item $item) {
        return $item->toArray();
      }, $this->items),
      'payments' => array_map(function (Payment $payment) {
        return $payment->toArray();
      }, $this->payments),
      'total' => round($this->total, 2),
    ];

    if (count($this->vats) > 0) {
      $result['vats'] = array_map(function (Vat $vat) {
        return $vat->toArray();
      }, $this->vats);
    }

    if (!is_null($this->agentInfo)) {
      $result['agent_info'] = $this->agentInfo->toArray();
    }

    if (!is_null($this->supplierInfo)) {
      $result['supplier_info'] = $this->supplierInfo->toArray();
    }

    if (!is_null($this->cashier)) {
      $result['cashier'] = $this->cashier;
    }

    if (!is_null($this->additionalUserProps)) {
      $result['additional_user_props'] = $this->additionalUserProps->toArray();
    }

    return $result;
  }
}
