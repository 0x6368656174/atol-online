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
use ItQuasar\AtolOnline\Exception\ClientException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Client
{
  /** @var string */
  private $host = 'https://online.atol.ru';

  /** @var string */
  private $apiVersion = 'v3';

  /** @var string */
  private $token;

  /** @var string */
  private $groupCode;

  /** @var LoggerInterface */
  private $logger;

  /**
   * @param string          $login     Shop ID
   * @param string          $password  Secret key
   * @param string          $groupCode Group code
   * @param LoggerInterface $logger    PSR Logger
   */
  public function __construct(string $login, string $password, string $groupCode, LoggerInterface $logger = null)
  {
    $this->groupCode = $groupCode;

    $this->token = $this->getToken($login, $password);
    $this->logger = $logger;
  }

  /**
   * @param string $value
   *
   * @return Client
   */
  public function setHost($value)
  {
    $this->host = $value;

    return $this;
  }

  /**
   * Отпаравляет запрос на регистрацию документа в АТОЛ Онлайн.
   *
   * Возвращает уникальный идентификатор, присвоенный данному документу.
   *
   * @param Request $request Запрос
   *
   * @return string Уникальный идентификатор, присвоенный данному документу
   */
  public function send(Request $request): string
  {
    $response = $this->sendRequest($request->getOperation(), $request->toArray());

    $error = $response['error'];
    if (null !== $error) {
      $this->log(LogLevel::WARNING, 'error: {error} {response}', [
        'error' => $error['text'],
        'response' => $response,
      ]);
      throw new ClientException($error['text']);
    }

    return $response['uuid'];
  }

  /**
   * Получает статуса обработки документа из АТОЛ Онлайн.
   *
   * Возвращает обработанный ответ.
   *
   * @param string $uuid Уникальный идентификатор, присвоенный документу после выполнения запроса на регистрацию
   *
   * @return Report
   */
  public function getReport(string $uuid): Report
  {
    $path = sprintf('report/%s', $uuid);
    $response = $this->sendRequest($path);

    return Report::fromArray($response);
  }

  /**
   * @param string $path
   * @param array  $data
   *
   * @return array|null
   */
  private function sendRequest(string $path, ?array $data = null): array
  {
    $path = sprintf('possystem/%s/%s/%s?tokenid=%s', $this->apiVersion, $this->groupCode, $path, $this->token);

    return $this->sendRawRequest($path, $data);
  }

  private function getToken(string $login, string $password): string
  {
    $data = [
      'login' => $login,
      'pass' => $password,
    ];

    $path = sprintf('possystem/%s/getToken', $this->apiVersion);

    $response = $this->sendRawRequest($path, $data);

    if ($response['code'] > 1) {
      $error = $response['text'];
      $this->log(LogLevel::WARNING, 'getToken error: {error} {response}', [
        'error' => $error,
        'response' => $response,
      ]);
      throw new ClientException($error);
    }

    return $response['token'];
  }

  /**
   * @param string     $path
   * @param array|null $data
   *
   * @return array
   */
  private function sendRawRequest(string $path, $data = null): array
  {
    if (null === $data) {
      $method = 'GET';
    } elseif (is_array($data)) {
      $method = 'POST';
      $data = json_encode($data);
    } else {
      throw new InvalidArgumentException('Unexpected type of $data, excepts array or null');
    }

    $url = sprintf('%s/%s', $this->host, $path);

    $headers = [
      'Accept: application/json',
    ];

    if ('POST' == $method) {
      $headers[] = 'Content-Type: application/json';
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if ('POST' == $method) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    $response = curl_exec($ch);

    $this->log(LogLevel::DEBUG, 'request: url={url} headers={headers} data={data}', [
      'url' => $url,
      'headers' => $headers,
      'data' => $data,
    ]);

    $error = null;
    if (false === $response) {
      $error = curl_error($ch);
    } else {
      $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      if (200 !== $status) {
        $error = sprintf('Unexpected status (%s)', $status);
      }
    }
    curl_close($ch);
    if (null !== $error) {
      $this->log(LogLevel::WARNING, 'error: {error} {response}', [
        'error' => $error,
        'response' => $response,
      ]);
      throw new ClientException($error);
    }

    $this->log(LogLevel::DEBUG, 'response: {response}', ['response' => $response]);

    return json_decode($response, true);
  }

  private function log($level, $message, $context)
  {
    if (null !== $this->logger) {
      $message = sprintf('Atol Online %s', $message);
      $this->logger->log($level, $message, $context);
    }
  }
}
