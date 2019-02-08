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
 * Атрибуты компании.
 */
class Company extends CorrectionCompany
{
  /** @var string|null */
  private $email = null;

  /**
   * Возвращает электронную почту отправителя.
   *
   * @return string
   */
  public function getEmail(): ?string
  {
    return $this->email;
  }

  /**
   * Устанавливает электронную почту отправителя.
   *
   * Максимальная длина строки – 64 символа
   *
   * @param string $email
   *
   * @return $this
   */
  public function setEmail(?string $email): self
  {
    if (mb_strlen($email) > 64) {
      throw new InvalidArgumentException('Email too big. Max length size = 64');
    }

    $this->email = $email;

    return $this;
  }

  public function toArray(): array
  {
    if (is_null($this->email)) {
      throw new SdkException('Email required');
    }

    $result = parent::toArray();
    $result['email'] = $this->email;

    return $result;
  }
}
