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

/**
 * Дополнительный реквизит пользователя.
 */
class AdditionalUserProps implements RequestPart
{
  /**
   * Возвращает наименование дополнительного реквизита пользователя.
   *
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Устанавлиает наименование дополнительного реквизита пользователя.
   *
   * Максимальная длина строки – 64 символа.
   *
   * @param string $name
   *
   * @return $this
   */
  public function setName(string $name): self
  {
    if (mb_strlen($name) > 64) {
      throw new InvalidArgumentException('Name too big. Max length size = 64');
    }

    $this->name = $name;

    return $this;
  }

  /**
   * Возвращает значение дополнительного реквизита пользователя.
   *
   * @return string
   */
  public function getValue(): string
  {
    return $this->value;
  }

  /**
   * Устанавлиает значение дополнительного реквизита пользователя.
   *
   * Максимальная длина строки – 256 символов.
   *
   * @param string $value
   *
   * @return $this;
   */
  public function setValue(string $value): self
  {
    if (mb_strlen($value) > 256) {
      throw new InvalidArgumentException('Value too big. Max length size = 256');
    }

    $this->value = $value;

    return $this;
  }

  /** @var null|string */
  private $name = null;

  /** @var null|string */
  private $value = null;

  public function toArray(): array
  {
    if (is_null($this->name)) {
      throw new SdkException('Name required');
    }

    if (is_null($this->value)) {
      throw new SdkException('Value required');
    }

    return [
      'name' => $this->name,
      'value' => $this->value,
    ];
  }
}
