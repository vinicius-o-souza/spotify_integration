<?php

namespace Drupal\spotify_integration\Service;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface to make HTTP Requests.
 */
interface HttpRequestInterface {

  /**
   * Make a GET request.
   *
   * @param string $path
   *   The API URL path.
   * @param array $params
   *   An array with parameters.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   The response object.
   */
  public function getRequest(string $path, array $params = []): ResponseInterface;

  /**
   * Make a POST request.
   *
   * @param string $path
   *   The API URL path.
   * @param array $params
   *   An array with parameters.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   The response object.
   */
  public function postRequest(string $path, array $params = []): ResponseInterface;

}
