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
use function is_null;
use function strlen;

/**
 * Служебный раздел.
 */
class Service implements RequestPart
{
  /** @var null|string */
  private $callbackUrl = null;


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
    $result = [];

    if (!is_null($this->callbackUrl)) {
      $result['callback_url'] = $this->callbackUrl;
    }

    return $result;
  }
}
