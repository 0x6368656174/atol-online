<?php
/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\AtolOnline;

use DateTime;
use ItQuasar\AtolOnline\Exception\SdkException;

/**
 * Коррекция.
 */
class CorrectionInfo implements RequestPart
{
  /** @var string Самостоятельно */
  const TYPE_SELF = 'self';

  /** @var string По предписанию */
  const TYPE_INSTRUCTION = 'instruction';

  /**
   * Возвращает тип коррекции.
   *
   * @return string
   */
  public function getType(): string
  {
    return $this->type;
  }

  /**
   * Устанавливает тип коррекии.
   *
   * Возможные значения:
   * @see CorrectionInfo::TYPE_SELF – самостоятельно;
   * @see CorrectionInfo::TYPE_INSTRUCTION - по предписанию.
   *
   * @param string $type
   *
   * @return $this
   */
  public function setType(string $type): self
  {
    $this->type = $type;

    return $this;
  }

  /**
   * Возвращает дату документа основания для коррекции
   *
   * @return DateTime
   */
  public function getBaseDate(): DateTime
  {
    return $this->baseDate;
  }

  /**
   * Устанавлиает дату документа основания для коррекции
   *
   * @param DateTime $baseDate
   *
   * @return $this
   */
  public function setBaseDate(DateTime $baseDate): self
  {
    $this->baseDate = $baseDate;

    return $this;
  }

  /**
   * Возвращает номер документа основания для коррекции.
   *
   * @return string
   */
  public function getBaseNumber(): string
  {
    return $this->baseNumber;
  }

  /**
   * Устанавливет номер документа основания для коррекции.
   *
   * @param string $baseNumber
   *
   * @return $this
   */
  public function setBaseNumber(string $baseNumber): self
  {
    $this->baseNumber = $baseNumber;

    return $this;
  }

  /**
   * Возвращает описание коррекции.
   *
   * @return string
   */
  public function getBaseName(): string
  {
    return $this->baseName;
  }

  /**
   * Устанавлиает описание коррекции.
   *
   * @param string $baseName
   *
   * @return $this
   */
  public function setBaseName(string $baseName): self
  {
    $this->baseName = $baseName;

    return $this;
  }

  /** @var string|null */
  private $type = null;

  /** @var null|DateTime */
  private $baseDate = null;

  /** @var null|string */
  private $baseNumber = null;

  /** @var null|string */
  private $baseName = null;

  public function toArray(): array
  {
    if (is_null($this->type)) {
      throw new SdkException('Type required');
    }

    if (is_null($this->baseDate)) {
      throw new SdkException('Base date required');
    }

    if (is_null($this->baseNumber)) {
      throw new SdkException('Base number required');
    }

    if (is_null($this->baseName)) {
      throw new SdkException('Base name required');
    }

    return [
      'type' => $this->type,
      'base_date' => $this->baseDate->format('d.m.Y'),
      'base_number' => $this->baseNumber,
      'base_name' => $this->baseName,
    ];
  }
}
