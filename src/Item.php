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
use ItQuasar\AtolOnline\Exception\SdkException;
use function is_null;

/**
 * Позиция чека.
 */
class Item implements RequestPart
{
  /** @var string */
  private $name = null;

  /** @var float */
  private $price = null;

  /** @var float */
  private $quantity = null;

  /** @var float */
  private $sum = null;

  /** @var null|string */
  private $measurementUnit = null;

  /** @var null|string */
  private $paymentMethod = null;

  /** @var null|string */
  private $paymentObject = null;

  /** @var null|Vat */
  private $vat = null;

  /** @var null|AgentInfo */
  private $agentInfo = null;

  /** @var null|SupplierInfo */
  private $supplierInfo = null;

  /** @var null|string */
  private $userData = null;

  /**
   * Создает позицию чека.
   *
   * @param null|string $name Название товара
   */
  public function __construct(?string $name = null)
  {
    $this->setName($name);
  }

  /**
   * Возвращает наименование товара.
   *
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Устанавливает наименование товара. Максимальная длина строки – 128 символов.
   *
   * @param string $name
   *
   * @return $this
   */
  public function setName(string $name): self
  {
    if (mb_strlen($name) > 128) {
      throw new InvalidArgumentException('Name too big. Max length size = 128');
    }

    $this->name = $name;

    return $this;
  }

  /**
   * Возвращает цену в рублях.
   *
   * @return float
   */
  public function getPrice(): float
  {
    return $this->price;
  }

  /**
   * Устанавлиает цену в рублях:
   * - целая часть не более 8 знаков;
   * - дробная часть не более 2 знаков.
   *
   * @param float $price
   *
   * @return $this
   */
  public function setPrice(float $price): self
  {
    if ($price > 99999999) {
      throw new InvalidArgumentException('Price too big. Max = 99999999');
    }

    $this->price = $price;

    return $this;
  }

  /**
   * Возвращает количество/вес
   *
   * @return float
   */
  public function getQuantity(): float
  {
    return $this->quantity;
  }

  /**
   * Устанавливает количество/вес:
   * - целая часть не более 8 знаков;
   * - дробная часть не более 3 знаков.
   *
   * @param float $quantity
   *
   * @return $this
   */
  public function setQuantity(float $quantity): self
  {
    if ($quantity > 99999999) {
      throw new InvalidArgumentException('Quantity too big. Max = 99999999');
    }

    $this->quantity = $quantity;

    return $this;
  }

  /**
   * Возвращает сумму позиции в рублях.
   *
   * @return float
   */
  public function getSum(): float
  {
    return $this->sum;
  }

  /**
   * Устанавливает сумму позиции в рублях:
   * - целая часть не более 8 знаков;
   * - дробная часть не более 2 знаков.
   *
   * Если значение sum меньше/больше значения (price*quantity), то разница является скидкой/надбавкой на позицию
   * соответственно. В этих случаях происходит перерасчёт поля price для равномерного распределения
   * скидки/надбавки по позициям.
   *
   * @param float $sum
   *
   * @return $this
   */
  public function setSum(float $sum): self
  {
    if ($sum > 99999999) {
      throw new InvalidArgumentException('Sum too big. Max = 99999999');
    }

    $this->sum = $sum;

    return $this;
  }

  /**
   * Возвращает единицу измерения товара, работы, услуги, платежа, выплаты, иного предмета расчета.
   *
   * @return string
   */
  public function getMeasurementUnit(): string {
    return $this->measurementUnit;
  }

  /**
   * Устанавлиает единицу измерения товара, работы, услуги, платежа, выплаты, иного предмета расчета.
   *
   * Максимальная длина строки – 16 символов
   *
   * @param string $unit
   *
   * @return $this
   */
  public function setMeasurementUnit(string $unit): self {
    if (mb_strlen($unit) > 16) {
      throw new InvalidArgumentException('MeasurementUnit too big. Max length size = 16');
    }

    $this->measurementUnit = $unit;

    return $this;
  }

  /**
   * Возвращает признак способа расчёта.
   *
   * @return string
   */
  public function getPaymentMethod(): string {
    return $this->paymentMethod;
  }

  /**
   * Устанавливает признак способа расчёта.
   *
   * Возможные значения:
   * - @see PaymentMethod::FULL_PREPAYMENT – предоплата 100%. Полная предварительная оплата до момента передачи предмета расчета.
   * - @see PaymentMethod::PREPAYMENT – предоплата. Частичная предварительная оплата до момента передачи предмета расчета.
   * - @see PaymentMethod::ADVANCE – аванс.
   * - @see PaymentMethod::FULL_PAYMENT – полный расчет. Полная оплата, в том числе с учетом аванса
   *   (предварительной оплаты) в момент передачи
   * - @see PaymentMethod::PARTIAL_PAYMENT – частичный расчет и кредит. Частичная оплата предмета расчета в момент его
   *   передачи с последующей оплатой в кредит.
   * - @see PaymentMethod::CREDIT – передача в кредит. Передача предмета расчета без его оплаты в момент его передачи с
   *   последующей оплатой в кредит.
   * - @see PaymentMethod::CREDIT_PAYMENT – оплата кредита. Оплата предмета расчета после его передачи с оплатой в
   *   кредит (оплата кредита). предмета расчета
   *
   * @param string $method
   *
   * @return $this
   */
  public function setPaymentMethod(string $method): self {
    $this->paymentMethod = $method;

    return $this;
  }

  /**
   * Возвращает признак предмета расчёта.
   *
   * @return string
   */
  public function getPaymentObject(): string {
    return $this->paymentObject;
  }

  /**
   * Устанавливает признак предмета расчёта.
   *
   * Возможные значения:
   * - @see PaymentObject::COMMODITY – товар. О реализуемом товаре, за исключением подакцизного товара
   *   (наименование и иные сведения, описывающие товар).
   * - @see PaymentObject::EXCISE – подакцизный товар. О реализуемом подакцизном товаре (наименование и иные сведения,
   *   описывающие товар).
   * - @see PaymentObject::JOB – работа. О выполняемой работе (наименование и иные сведения, описывающие работу).
   * - @see PaymentObject::SERVICE – услуга. Об оказываемой услуге (наименование и иные сведения, описывающие услугу).
   * - @see PaymentObject::GAMBLING_BET – ставка азартной игры. О приеме ставок при осуществлении деятельности по
   *   проведению азартных игр.
   * - @see PaymentObject::GAMBLING_PRIZE – выигрыш азартной игры. О выплате денежных средств в виде выигрыша при
   *   осуществлении деятельности по проведению азартных игр.
   * - @see PaymentObject::LOTTERY – лотерейный билет. О приеме денежных средств при реализации лотерейных билетов,
   *   электронных лотерейных билетов, приеме лотерейных ставок при осуществлении деятельности по проведению лотерей.
   * - @see PaymentObject::LOTTERY_PRIZE – выигрыш лотереи. О выплате денежных средств в виде выигрыша при осуществлении
   *   деятельности по проведению лотерей.
   * - @see PaymentObject::INTELLECTUAL_ACTIVITY – предоставление результатов интеллектуальной деятельности. О предоставлении
   *   прав на использование результатов интеллектуальной деятельности или средств индивидуализации.
   * - @see PaymentObject::PAYMENT – платеж. Об авансе, задатке, предоплате, кредите, взносе в счет оплаты, пени, штрафе,
   *   вознаграждении, бонусе и ином аналогичном предмете расчета.
   * - @see PaymentObject::AGENT_COMMISSION – агентское вознаграждение. О вознаграждении пользователя, являющегося платежным
   *   агентом (субагентом), банковским платежным агентом (субагентом), комиссионером, поверенным или иным агентом.
   * - @see PaymentObject::COMPOSITE – составной предмет расчета. О предмете расчета, состоящем из предметов, каждому из
   *   которых может быть присвоено значение выше перечисленных признаков.
   * - @see PaymentObject::ANOTHER – иной предмет расчета. О предмете расчета, не относящемуся к выше перечисленным предметам расчета.
   * - @see PaymentObject::PROPERTY_RIGHT – имущественное право. О передаче имущественных прав.
   * - @see PaymentObject::NON_OPERATING_GAIN – внереализационный доход. О внереализационном доходе.
   * - @see PaymentObject::INSURANCE_PREMIUM – страховые взносы. О суммах расходов, уменьшающих сумму налога (авансовых
   *   платежей) в соответствии с пунктом 3.1 статьи 346.21 Налогового кодекса Российской Федерации.
   * - @see PaymentObject::SALES_TAX – торговый сбор. О суммах уплаченного торгового сбора.
   * - @see PaymentObject::RESORT_FEE – курортный сбор. О курортном сборе.
   *
   * @param string $object
   *
   * @return $this
   */
  public function setPaymentObject(string $object): self {
    $this->paymentObject = $object;

    return $this;
  }

  /**
   * Возвращает атрибуты налога на позицию
   *
   * @return Vat
   */
  public function getVat(): Vat {
    return $this->vat;
  }

  /**
   * Устанавлиает атрибуты налога на позицию.
   *
   * Необходимо передать либо сумму налога на позицию, либо сумму налога на чек. Если будут переданы и
   * сумма налога на позицию и сумма налога на чек, сервис учтет только сумму налога на чек.
   *
   * @param Vat $vat
   *
   * @return $this
   */
  public function setVat(Vat $vat): self {
    $this->vat = $vat;

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
   * Возвращает дополнительный реквизит предмета расчета.
   *
   * @return string|null
   */
  public function getUserData(): ?string {
    return $this->userData;
  }

  /**
   * Устанавливает дополнительный реквизит предмета расчета.
   *
   * @param string|null $userData
   *
   * @return Item
   */
  public function setUserData(?string $userData): self {
    if (mb_strlen($userData) > 64) {
      throw new InvalidArgumentException('User data too big. Max length size = 64');
    }

    $this->userData = $userData;

    return $this;
  }

  public function toArray(): array
  {
    if (is_null($this->name)) {
      throw new SdkException('Name required');
    }

    if (is_null($this->price)) {
      throw new SdkException('Price required');
    }

    if (is_null($this->sum)) {
      throw new SdkException('Sum required');
    }

    if (is_null($this->vat)) {
      throw new SdkException('Vat required');
    }

    if (is_null($this->paymentMethod)) {
      throw new SdkException('Payment method required');
    }

    if (is_null($this->paymentObject)) {
      throw new SdkException('Payment object required');
    }

    if (!is_null($this->agentInfo) && is_null($this->supplierInfo)) {
      throw new SdkException('Supplier info required if agent info sets.');
    }

    if (!is_null($this->supplierInfo) && is_null($this->agentInfo)) {
      throw new SdkException('Agent info required if supplier info sets.');
    }

    $result = [
      'name' => $this->name,
      'price' => round($this->price, 2),
      'quantity' => round($this->quantity, 3),
      'sum' => round($this->sum, 2),
      'vat' => $this->vat->toArray(),
      'payment_method' => $this->paymentMethod,
      'payment_object' => $this->paymentObject,
    ];

    if (!is_null($this->measurementUnit)) {
      $result['measurement_unit'] = $this->measurementUnit;
    }

    if (!is_null($this->agentInfo)) {
      $result['agent_info'] = $this->agentInfo->toArray();
    }

    if (!is_null($this->supplierInfo)) {
      $result['supplier_info'] = $this->supplierInfo->toArray();
    }

    if (!is_null($this->userData)) {
      $result['user_data'] = $this->userData;
    }

    return $result;
  }
}
