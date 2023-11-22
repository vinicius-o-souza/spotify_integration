<?php

namespace Drupal\spotify_integration\Service\Resource;

/**
 * Request artists information from the Spotify APIs.
 */
interface ResourceInterface {

  /**
   * Save the resource with the Spotify ids.
   *
   * @param array $ids
   *   The ids of the resorces requested.
   */
  public function save(array $ids): void;

}
