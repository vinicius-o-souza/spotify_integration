<?php

namespace Drupal\spotify_integration\Service;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class that makes HTTP Requests.
 */
class HttpRequest implements HttpRequestInterface {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * The spotify_integration config service.
   *
   * @var \Drupal\spotify_integration\Service\SpotifyConfigService
   */
  protected $configService;

  /**
   * Constructs a Request object.
   *
   * @param \GuzzleHttp\ClientInterface $client
   *   The HTTP client.
   * @param \Drupal\spotify_integration\Service\SpotifyConfigService $configService
   *   The spotify_integration config service.
   */
  public function __construct(ClientInterface $client, SpotifyConfigService $configService) {
    $this->client = $client;
    $this->configService = $configService;
  }

  /**
   * {@inheritdoc}
   */
  public function getRequest(string $path, array $params = []): ResponseInterface {
    $url = $this->getUrl($path);
    $params = array_merge($params, [
      'headers' => [
        'Authorization' => 'Bearer ' . $this->configService->getToken(),
      ],
    ]);

    return $this->client->request('GET', $url, $params);
  }

  /**
   * {@inheritdoc}
   */
  public function postRequest(string $path, array $params = []): ResponseInterface {
    $url = $this->getUrl($path);
    return $this->client->request('POST', $url, $params);
  }

  /**
   * Get the Spotify Web API URL.
   *
   * @param string $path
   *   The path to generate the URL.
   *
   * @return string
   *   The URL complete.
   */
  protected function getUrl(string $path): string {
    $url = $this->configService->getSpotifyUrl();
    if (!str_ends_with($url, '/')) {
      $url = $url . '/';
    }
    return $url . $path;
  }

}
