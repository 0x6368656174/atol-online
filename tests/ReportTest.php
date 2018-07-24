<?php

declare(strict_types=1);

/*
 * This file is part of the it-quasar/atol-online library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ItQuasar\AtolOnline\Report;
use PHPUnit\Framework\TestCase;

final class ReportTest extends TestCase
{
  public function testCanParseValue()
  {
    $dir = __DIR__;
    $data = file_get_contents("$dir/report-response.json");
    $dataArray = json_decode($data, true);

    $report = Report::fromArray($dataArray);

    $this->assertEquals('2ea26f17-0884-4f08-b120-306fc096a58f', $report->getUuid());
    $this->assertEquals(null, $report->getError());
    $this->assertEquals(Report::STATUS_DONE, $report->getStatus());
    $this->assertEquals(1598, $report->getPayload()->getTotal());
    $this->assertEquals('example.com', $report->getPayload()->getFnsSite());
    $this->assertEquals('1110000100238211', $report->getPayload()->getFnNumber());
    $this->assertEquals(23, $report->getPayload()->getShiftNumber());
    $this->assertEquals('2017.04.12 20:16:00', $report->getPayload()->getReceiptDatetime()->format('Y.m.d H:i:s'));
    $this->assertEquals(6, $report->getPayload()->getFiscalReceiptNumber());
    $this->assertEquals(133, $report->getPayload()->getFiscalDocumentNumber());
    $this->assertEquals('0000111118041361', $report->getPayload()->getEcrRegistrationNumber());
    $this->assertEquals(3449555941, $report->getPayload()->getFiscalDocumentAttribute());
    $this->assertEquals('12.04.2017 20:15:08', $report->getTimestamp()->format('d.m.Y H:i:s'));
    $this->assertEquals('MyCompany_MyShop', $report->getGroupCode());
    $this->assertEquals('prod-agent-1', $report->getDaemonCode());
    $this->assertEquals('KSR13.00-1-11', $report->getDeviceCode());
    $this->assertEquals('', $report->getCallbackUrl());
  }
}
