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
 * Атрибуты чека.
 */
class ReceiptAttributes implements RequestPart
{
  /** @var string */
  private $sno = null;

  /** @var string|null */
  private $email = null;

  /** @var string|null */
  private $phone = null;

  /**
   * Возвращает систему налогообложения.
   *
   * @return string
   */
  public function getSno(): string
  {
    return $this->sno;
  }

  /**
   * Устанавливает систему налогообложения.
   *
   * Перечисление со значениями:
   * - SnoSystem::OSN – общая СН;
   * - SnoSystem::USN_INCOME – упрощенная СН (доходы);
   * - SnoSystem::USN_INCOME_OUTCOME – упрощенная СН (доходы минус расходы);
   * - SnoSystem::ENVD – единый налог на вмененный доход;
   * - SnoSystem::ESN – единый сельскохозяйственный налог;
   * - SnoSystem::PATENT – патентная СН
   *
   * Поле необязательно, если у организации один тип налогообложения.
   *
   * @param string
   */
  public function setSno($sno): void
  {
    $this->sno = $sno;
  }

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
   */
  public function setEmail(?string $email): void
  {
    if (strlen($email) > 64) {
      throw new InvalidArgumentException('Email too big. Max length size = 64');
    }

    $this->email = $email;
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
   */
  public function setPhone(?string $phone): void
  {
    if (strlen($phone) > 64) {
      throw new InvalidArgumentException('Phone too big. Max length size = 64');
    }

    if (!preg_match('/\+?\d+/', $phone)) {
      throw new InvalidArgumentException('Phone must be format as +37121234567');
    }

    $this->phone = $phone;
  }

  public function toArray(): array
  {
    if (is_null($this->email) && is_null($this->phone)) {
      throw new SdkException('Email or phone required');
    }

    $result = [];

    if (!is_null($this->sno)) {
      $result['sno'] = $this->sno;
    }

    if (!is_null($this->email)) {
      $result['email'] = $this->email;
    }

    if (!is_null($this->phone)) {
      $result['phone'] = $this->phone;
    }

    return $result;
  }
}
