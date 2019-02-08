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
 * Атрибуты оператора по приему платежей.
 */
class ReceivePaymentsOperator implements RequestPart
{
  /** @var string[] */
  private $phones = [];

  /**
   * Возвращает телефоны оператора по приему платежей.
   *
   * @return string[]
   */
  public function getPhones(): array
  {
    return $this->phones;
  }

  /**
   * Устанавливает телефоны оператора по приему платежей.
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
   * Добавляет телефон оператора по приему платежей.
   *
   * @param string $phone
   *
   * @return $this
   */
  public function addPhone(string  $phone): self {
    $this->phones []= $phone;

    return $this;
  }

  public function toArray(): array
  {
    $result = [];

    if (count($this->phones) !== 0) {
      $result['phones'] = $this->phones;
    }

    return $result;
  }
}
