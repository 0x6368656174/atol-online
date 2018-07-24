<?php

declare(strict_types=1);

/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ItQuasar\AtolOnline;

use ItQuasar\AtolOnline\Exception\SdkException;
use function is_null;

/**
 * Атрибуты коррекции.
 */
class CorrectionAttributes implements RequestPart
{
  /**
   * @var string
   */
  private $sno = null;

  /**
   * @var string|null
   */
  private $tax = null;

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
   * Возвращает номер налога в ККТ.
   *
   * @return string
   */
  public function getTax(): string
  {
    return $this->tax;
  }

  /**
   * Устанавливает номер налога в ККТ.
   *
   * Перечисление со значениями:
   * - TaxSystem::NONE – без НДС;
   * - TaxSystem::VAT0 – НДС по ставке 0%;
   * - TaxSystem::VAT10 – НДС чека по ставке 10%;
   * - TaxSystem::VAT18 – НДС чека по ставке 18%;
   * - TaxSystem::VAT110 – НДС чека по расчетной ставке 10/110;
   * - TaxSystem::VAT118 – НДС чека по расчетной ставке 18/118.
   *
   * @param string
   */
  public function setTax(string $tax): void
  {
    $this->tax = $tax;
  }

  public function toArray(): array
  {
    if (is_null($this->tax)) {
      throw new SdkException('Tax required');
    }

    $result = [
      'tax' => $this->tax,
    ];

    if (!is_null($this->sno)) {
      $result['sno'] = $this->sno;
    }

    return $result;
  }
}
