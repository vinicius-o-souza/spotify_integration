<?php

namespace Drupal\spotify_integration\Service\Resource;

/**
 * Request resources information from the Spotify APIs.
 */
class ResourceFactoryService {

  /**
   * The spotify_integration album request service.
   *
   * @var \Drupal\spotify_integration\Service\Resource\AlbumService
   */
  protected $albumRequest;

  /**
   * The spotify_integration artist request service.
   *
   * @var \Drupal\spotify_integration\Service\Resource\ArtistService
   */
  protected $artistRequest;

  /**
   * The spotify_integration songs request service.
   *
   * @var \Drupal\spotify_integration\Service\Resource\SongService
   */
  protected $songRequest;

  /**
   * Constructs a ResourceFactorService object.
   *
   * @param \Drupal\spotify_integration\Service\Resource\AlbumService $albumRequest
   *   The spotify_integration album request service.
   * @param \Drupal\spotify_integration\Service\Resource\ArtistService $artistRequest
   *   The spotify_integration artists request service.
   * @param \Drupal\spotify_integration\Service\Resource\SongService $songRequest
   *   The spotify_integration songs request service.
   */
  public function __construct(
    AlbumService $albumRequest,
    ArtistService $artistRequest,
    SongService $songRequest
  ) {
    $this->albumRequest = $albumRequest;
    $this->artistRequest = $artistRequest;
    $this->songRequest = $songRequest;
  }

  /**
   * Get the resource service object.
   *
   * @param string $bundle
   *   The bundle string.
   *
   * @return \Drupal\spotify_integration\Service\Resource\ResourceInterface|null
   *   The resource object.
   */
  public function getResourceService(string $bundle): ?ResourceInterface {
    switch ($bundle) {
      case 'album':
        return $this->albumRequest;

      case 'artist':
        return $this->artistRequest;

      case 'song':
        return $this->songRequest;

      default:
        return NULL;
    }
  }

}
