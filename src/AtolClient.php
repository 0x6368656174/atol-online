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
use ItQuasar\AtolOnline\Exception\ClientException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\SimpleCache\CacheInterface;

class AtolClient
{
  /** @var string */
  private $host = 'https://online.atol.ru';

  /** @var string */
  private $apiVersion = 'v4';

  /** @var string */
  private $token;

  /** @var string */
  private $groupCode;

  /** @var LoggerInterface */
  private $logger;

  /** @var CacheInterface */
  private $cache;

  /**
   * @param string $login Shop ID
   * @param string $password Secret key
   * @param string $groupCode Group code
   * @param CacheInterface $cache
   * @param LoggerInterface $logger PSR Logger
   */
  public function __construct(string $login, string $password, string $groupCode, CacheInterface $cache, LoggerInterface $logger = null)
  {
    $this->groupCode = $groupCode;

    $this->logger = $logger;
    $this->cache = $cache;
    $this->token = $this->getToken($login, $password);
  }

  /**
   * @param string $value
   *
   * @return $this
   */
  public function setHost($value): self
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
      if (null == $response['uuid']) {
        throw new ClientException($error['error_id'].' - '.$error['text']);
      }
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
    $path = sprintf('possystem/%s/%s/%s', $this->apiVersion, $this->groupCode, $path);

    return $this->sendRawRequest($path, $data);
  }

  private function getToken(string $login, string $password): string
  {
    $CACHE_KEY = 'atol.token' . str_replace('-', '_' ,$login);
    if($this->cache->has($CACHE_KEY)) {
      return (string)$this->cache->get($CACHE_KEY);
    }

    $data = [
      'login' => $login,
      'pass' => $password,
    ];

    $path = sprintf('possystem/%s/getToken', $this->apiVersion);

    $response = $this->sendRawRequest($path, $data);

    $error = $response['error'];
    if (null !== $error) {
      $this->log(LogLevel::WARNING, 'error: {error} {response}', [
        'error' => $error['text'],
        'response' => $response,
      ]);
      throw new ClientException($error['error_id'].' - '.$error['text']);
    }

    $token = $response['token'];
    $this->cache->set($CACHE_KEY, $token, 3600 * 24);

    return $token;
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
      'Accept: application/json; charset=utf-8',
      'Token: '.$this->token,
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
