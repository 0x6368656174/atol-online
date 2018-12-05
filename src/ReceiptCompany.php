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
 * Атрибуты компании.
 */
class ReceiptCompany implements RequestPart
{
  /** @var string */
  private $sno = null;

  /** @var string|null */
  private $email = null;

  /** @var string|null */
  private $inn = null;

  /** @var null string|null */
  private $paymentAddress = null;

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
   *
   * @return $this
   */
  public function setSno($sno): self
  {
    $this->sno = $sno;

    return $this;
  }

  /**
   * Возвращает ИНН организации.
   *
   * @return null|string
   */
  public function getInn(): ?string
  {
    return $this->inn;
  }

  /**
   * Устанавливает ИНН организации.
   *
   * Используется для предотвращения ошибочных регистраций чеков на ККТ зарегистрированных с другим
   * ИНН (сравнивается со значением в ФН).
   *
   * Допустимое количество символов 10 или 12
   *
   * @param null|string $inn
   *
   * @return $this
   */
  public function setInn(?string $inn): self {
    $length = strlen($inn);
    if (10 !== $length && 12 !== $length) {
      throw new InvalidArgumentException('Inn must be length = 10 or length = 12');
    }

    $this->inn = $inn;

    return $this;
  }

  /**
   * Возвращает адрес места расчетов
   *
   * @return string|null
   */
  public function getPaymentAddress(): ?string
  {
    return $this->paymentAddress;
  }

  /**
   * Устанавлиает адрес места расчетов.
   *
   * Используется для предотвращения ошибочных регистраций чеков на ККТ зарегистрированных с другим
   * адресом места расчёта (сравнивается со значением в ФН).
   *
   * Максимальная длина строки - 256 символов
   *
   * @param string|null $paymentAddress
   *
   * @return $this
   */
  public function setPaymentAddress(?string $paymentAddress): self{
    if (strlen($paymentAddress) > 256) {
      throw new InvalidArgumentException('Payment address too big. Max length size = 256');
    }

    $this->paymentAddress = $paymentAddress;

    return $this;
  }

  /**
   * Возвращает электронную почту отправителя.
   *
   * @return null|string
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

  public function toArray(): array
  {
    if (is_null($this->email)) {
      throw new SdkException('Email required');
    }

    if (is_null($this->inn)) {
      throw new SdkException('Inn required');
    }

    if (is_null($this->paymentAddress)) {
      throw new SdkException('PaymentAddress required');
    }

    $result = [
      'inn' => $this->inn,
      'payment_address' => $this->paymentAddress,
    ];

    if (!is_null($this->sno)) {
      $result['sno'] = $this->sno;
    }

    if (!is_null($this->email)) {
      $result['email'] = $this->email;
    }

    return $result;
  }
}
