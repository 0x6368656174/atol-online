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
 * Атрибуты оператора перевода.
 */
class MoneyTransferOperator implements RequestPart
{
  /** @var string|null */
  private $name = null;

  /** @var string[] */
  private $phones = [];

  /** @var null|string */
  private $address = null;

  /** @var null|string */
  private $inn = null;

  /**
   * Возвращает телефоны наименование оператора перевода.
   *
   * @return string[]
   */
  public function getPhones(): array
  {
    return $this->phones;
  }

  /**
   * Устанавливает телефоны наименование оператора перевода.
   *
   * @param array $phones
   *
   * @return $this
   */
  public function setPhones(array $phones): self {
    $this->phones = $phones;

    return $this;
  }

  /**
   * Добавляет телефон наименование оператора перевода.
   *
   * @param string $phone
   *
   * @return $this
   */
  public function addPhone(string $phone): self {
    $this->phones []= $phone;

    return $this;
  }

  /**
   * Возвращает наименование оператора перевода.
   *
   * @return string|null
   */
  public function getName(): ?string
  {
    return $this->name;
  }

  /**
   * Устанавливает наименование оператора перевода.
   *
   * @param string|null $name
   *
   * @return $this
   */
  public function setName(?string $name): self
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Возвращает ИНН оператора перевода.
   *
   * @return string|null
   */
  public function getInn(): ?string
  {
    return $this->inn;
  }

  /**
   * Устанавливает ИНН оператора перевода.
   *
   * @param string|null $inn
   *
   * @return $this
   */
  public function setInn(?string $inn): self {
    $this->inn = $inn;

    return $this;
  }

  /**
   * Возвращает адрес оператора перевода.
   *
   * @return string|null
   */
  public function getAddress(): ?string {
    return $this->address;
  }

  /**
   * Устанавлиает адрес оператора перевода.
   *
   * @param string|null $address
   *
   * @return $this
   */
  public function setAddress(?string $address): self {
    $this->address = $address;

    return $this;
  }

  public function toArray(): array
  {
    $result = [];

    if (count($this->phones) !== 0) {
      $result['phones'] = $this->phones;
    }

    if (!is_null($this->name)) {
      $result['name'] = $this->name;
    }

    if (!is_null($this->inn)) {
      $result['inn'] = $this->inn;
    }

    if (!is_null($this->address)) {
      $result['address'] = $this->address;
    }

    return $result;
  }

}
