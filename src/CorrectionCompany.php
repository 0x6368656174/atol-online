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
class CorrectionCompany implements RequestPart
{
  /** @var string */
  protected $sno = null;

  /** @var string|null */
  protected $inn = null;

  /** @var null string|null */
  protected $paymentAddress = null;

  /**
   * Возвращает систему налогообложения.
   *
   * @return null|string
   */
  public function getSno(): ?string
  {
    return $this->sno;
  }

  /**
   * Устанавливает систему налогообложения.
   *
   * Перечисление со значениями:
   * - @see SnoSystem::OSN – общая СН;
   * - @see SnoSystem::USN_INCOME – упрощенная СН (доходы);
   * - @see SnoSystem::USN_INCOME_OUTCOME – упрощенная СН (доходы минус расходы);
   * - @see SnoSystem::ENVD – единый налог на вмененный доход;
   * - @see SnoSystem::ESN – единый сельскохозяйственный налог;
   * - @see SnoSystem::PATENT – патентная СН
   *
   * Поле необязательно, если у организации один тип налогообложения.
   *
   * @param null|string
   *
   * @return $this
   */
  public function setSno(?string $sno): self
  {
    $this->sno = $sno;

    return $this;
  }

  /**
   * Возвращает ИНН организации.
   *
   * @return string
   */
  public function getInn(): string
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
   * @param string $inn
   *
   * @return $this
   */
  public function setInn(string $inn): self {
    $length = mb_strlen($inn);
    if (10 !== $length && 12 !== $length) {
      throw new InvalidArgumentException('Inn must be length = 10 or length = 12');
    }

    $this->inn = $inn;

    return $this;
  }

  /**
   * Возвращает адрес места расчетов
   *
   * @return string
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
   * @param string $paymentAddress
   *
   * @return $this
   */
  public function setPaymentAddress(?string $paymentAddress): self {
    if (mb_strlen($paymentAddress) > 256) {
      throw new InvalidArgumentException('Payment address too big. Max length size = 256');
    }

    $this->paymentAddress = $paymentAddress;

    return $this;
  }

  public function toArray(): array
  {
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

    return $result;
  }
}
