<?php
/**
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use ItQuasar\AtolOnline\SellCorrection;
use PHPUnit\Framework\TestCase;

require_once ('common.php');

final class SellCorrectionTest extends TestCase
{
  public function testCanReturnValidArray()
  {
    $dir = __DIR__;
    $expectedDateJson = file_get_contents("$dir/buy-correction-request.json");
    $expectedDate = json_decode($expectedDateJson, true);

    $request = new SellCorrection();
    createCorrectionRequest($request);

    $this->assertEquals($expectedDate, $request->toArray());
  }

  public function testCatValidOperation()
  {
    $request = new SellCorrection();

    $this->assertEquals('sell_correction', $request->getOperation());
  }
}
