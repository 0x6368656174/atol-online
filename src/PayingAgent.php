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

/**
 * Атрибуты платежного агента
 */
class PayingAgent implements RequestPart
{
  /** @var string|null */
  private $operation;

  /** @var string[] */
  private $phones = [];

  /**
   * Возвращает наименование операции.
   *
   * @return string|null
   */
  public function getOperation(): ?string {
    return $this->operation;
  }

  /**
   * Устанавлиает наименование операции.
   *
   * Максимальная длина строки – 24 символа.
   *
   * @param string|null $operation
   *
   * @return $this
   */
  public function setOperation(?string $operation): self {
    if (mb_strlen($operation) > 24) {
      throw new InvalidArgumentException('Operation too big. Max length size = 24');
    }

    $this->operation = $operation;

    return $this;
  }

  /**
   * Возвращает телефоны платежного агента.
   *
   * @return string[]
   */
  public function getPhones(): array
  {
    return $this->phones;
  }

  /**
   * Устанавливает телефоны платежного агента.
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
   * Добавляет телефон платежного агента.
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

    if (!is_null($this->operation)) {
      $result['operation'] = $this->operation;
    }

    if (count($this->phones) !== 0) {
      $result['phones'] = $this->phones;
    }

    return $result;
  }
}
