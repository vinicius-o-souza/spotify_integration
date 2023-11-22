<?php

namespace Drupal\spotify_integration\Service;

use Drupal\Core\Logger\LoggerChannelTrait;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Make requests to the Spotify APIs.
 */
class SpotifyRequest extends HttpRequest {

  use LoggerChannelTrait;

  /**
   * Make the request to login into the Spotify Web APIs.
   *
   * @return bool
   *   Boolean indicating if the login was successful or not.
   */
  public function login(): bool {
    $clientId = $this->configService->getClientId();
    $clientSecret = $this->configService->getClientSecret();

    try {
      $response = $this->client->request('POST', 'https://accounts.spotify.com/api/token', [
        'headers' => [
          'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'form_params' => [
          'grant_type' => 'client_credentials',
          'client_id' => $clientId,
          'client_secret' => $clientSecret,
        ],
      ]);

      $body = json_decode($response->getBody()->getContents(), TRUE);
      $this->configService->saveToken($body['access_token']);
    }
    catch (\Exception $e) {
      $this->getLogger('spotify_integration')->error($e->getTraceAsString());
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Get the list of artists by id.
   *
   * @param string $resource
   *   The resource name.
   * @param array $ids
   *   The ids of the artists requested.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   The response object.
   */
  public function getResource(string $resource, array $ids): ResponseInterface {
    $token = $this->configService->getToken();
    if (!$token) {
      $this->login();
    }
    $ids = implode(',', $ids);
    try {
      $response = $this->getRequest($resource, [
        'query' => [
          'ids' => $ids,
          'market' => 'ES',
        ],
      ]);
    }
    catch (GuzzleException $e) {
      // If the token is expired.
      if ($e->getCode() === Response::HTTP_UNAUTHORIZED) {
        $this->login();
      }
      $response = $this->getRequest($resource, [
        'query' => [
          'ids' => $ids,
          'market' => 'ES',
        ],
      ]);
    }

    return $response;
  }

}
