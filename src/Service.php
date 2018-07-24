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
use function strlen;

/**
 * Служебный раздел.
 */
class Service implements RequestPart
{
  /** @var string */
  private $inn = null;

  /** @var string */
  private $paymentAddress = null;

  /** @var null|string */
  private $callbackUrl = null;

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
   * @return Service
   */
  public function setInn(string $inn): self
  {
    $length = strlen($inn);
    if (10 !== $length && 12 !== $length) {
      throw new InvalidArgumentException('Inn must be length = 10 or length = 12');
    }

    $this->inn = $inn;

    return $this;
  }

  /**
   * Возвращает адрес места расчетов.
   *
   * @return string
   */
  public function getPaymentAddress(): string
  {
    return $this->paymentAddress;
  }

  /**
   * Устанавливает адрес места расчетов.
   *
   * Используется для предотвращения ошибочных регистраций чеков на ККТ зарегистрированных с другим
   * адресом места расчёта (сравнивается со значением в ФН).
   *
   * Максимальная длина строки – 256 символов.
   *
   * @param string $paymentAddress
   *
   * @return Service
   */
  public function setPaymentAddress(string $paymentAddress): self
  {
    if (strlen($paymentAddress) > 256) {
      throw new InvalidArgumentException('PaymentAddress too big. Max length size = 256');
    }

    $this->paymentAddress = $paymentAddress;

    return $this;
  }

  /**
   * Возвращает URL, на который необходимо ответить после обработки документа.
   *
   * @return null|string
   */
  public function getCallbackUrl(): ?string
  {
    return $this->callbackUrl;
  }

  /**
   * Устанавливает URL, на который необходимо ответить после обработки документа.
   *
   * Если поле заполнено, то после обработки документа (успешной или не успешной фискализации в ККТ),
   * ответ будет отправлен POST запросом по URL указанному в данном поле.
   *
   * Максимальная длина строки – 256 символов
   *
   * @param null|string $callbackUrl
   *
   * @return Service
   */
  public function setCallbackUrl(?string $callbackUrl): self
  {
    if (strlen($callbackUrl) > 256) {
      throw new InvalidArgumentException('CallbackUrl too big. Max length size = 256');
    }

    $this->callbackUrl = $callbackUrl;

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

    if (!is_null($this->callbackUrl)) {
      $result['callback_url'] = $this->callbackUrl;
    }

    return $result;
  }
}
