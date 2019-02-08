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
 * Атрибуты поставщика.
 */
class SupplierInfo implements RequestPart
{
  /** @var string[] */
  private $phones = [];

  /** @var string|null */
  private $name = null;

  /** @var null|string */
  private $inn = null;

  /**
   * Возвращает телефоны поставщика.
   *
   * @return string[]
   */
  public function getPhones(): array
  {
    return $this->phones;
  }

  /**
   * Устанавливает телефоны поставщика.
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
   * Добавляет телефон поставщика.
   *
   * @param string $phone
   *
   * @return $this
   */
  public function addPhone(string  $phone): self {
    $this->phones []= $phone;

    return $this;
  }

  /**
   * Возвращает наименование поставщика.
   *
   * @return string|null
   */
  public function getName(): ?string
  {
    return $this->name;
  }

  /**
   * Устанавливает наименование поставщика.
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
   * Возвращает ИНН поставщика.
   *
   * @return string|null
   */
  public function getInn(): ?string
  {
    return $this->inn;
  }

  /**
   * Устанавливает ИНН поставщика.
   *
   * @param string|null $inn
   *
   * @return $this
   */
  public function setInn(?string $inn): self {
    $this->inn = $inn;

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

    return $result;
  }
}
