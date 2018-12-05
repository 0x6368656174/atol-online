<?php

declare(strict_types=1);

/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ItQuasar\AtolOnline;

use InvalidArgumentException;
use ItQuasar\AtolOnline\Exception\SdkException;
use function is_null;
use function preg_match;

/**
 * Атрибуты клиента.
 */
class ReceiptClient implements RequestPart
{
  /** @var string|null */
  private $email = null;

  /** @var string|null */
  private $phone = null;

  /**
   * Возвращает электронную почту покупателя.
   *
   * @return null|string
   */
  public function getEmail(): ?string
  {
    return $this->email;
  }

  /**
   * Устанавливает электронную почту покупателя.
   *
   * Максимальная длина строки – 64 символа
   *
   * В запросе обязательно должно быть заполнено хотя бы одно из полей: email или phone.
   *
   * @param null|string $email
   *
   * @return $this
   */
  public function setEmail(?string $email): self
  {
    if (strlen($email) > 64) {
      throw new InvalidArgumentException('Email too big. Max length size = 64');
    }

    $this->email = $email;

    return $this;
  }

  /**
   * Возвращает Телефон покупателя.
   *
   * @return null|string
   */
  public function getPhone(): ?string
  {
    return $this->phone;
  }

  /**
   * Устанавливает телефон покупателя.
   *
   *Номер телефона необходимо передать вместе с кодом страны без пробелов и дополнительных символов, кроме
   * символа «+» (номер «+371 2 1234567» необходимо передать как «+37121234567»). Если номер телефона относится к России
   * (префикс «+7»), то значение можно передать без префикса (номер «+7 925 1234567» можно передать как «9251234567»).
   *
   * Максимальная длина строки – 64 символа.
   *
   * В запросе обязательно должно быть заполнено хотя бы одно из полей: email или phone.
   *
   * @param null|string $phone
   *
   * @return $this
   */
  public function setPhone(?string $phone): self
  {
    if (strlen($phone) > 64) {
      throw new InvalidArgumentException('Phone too big. Max length size = 64');
    }

    if (!preg_match('/\+?\d+/', $phone)) {
      throw new InvalidArgumentException('Phone must be format as +37121234567');
    }

    $this->phone = $phone;

    return $this;
  }

  public function toArray(): array
  {
    if (is_null($this->email) && is_null($this->phone)) {
      throw new SdkException('Email or phone required');
    }

    $result = [];

    if (!is_null($this->email)) {
      $result['email'] = $this->email;
    }

    if (!is_null($this->phone)) {
      $result['phone'] = $this->phone;
    }

    return $result;
  }
}
