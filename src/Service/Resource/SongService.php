<?php

namespace Drupal\spotify_integration\Service\Resource;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\spotify_integration\Model\Album;
use Drupal\spotify_integration\Model\Song;
use Drupal\spotify_integration\Service\SpotifyRequest;

/**
 * Request songs information from the Spotify APIs.
 */
class SongService extends ResourceService {

  /**
   * The spotify_integration artists request service.
   *
   * @var \Drupal\spotify_integration\Service\Resource\ArtistService
   */
  protected $artistRequest;

  /**
   * Constructs a Request object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   * @param \Drupal\spotify_integration\Service\SpotifyRequest $spotifyRequest
   *   The spotify_integration request service.
   * @param \Drupal\spotify_integration\Service\Resource\ArtistService $artistRequest
   *   The spotify_integration artists request service.
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    SpotifyRequest $spotifyRequest,
    ArtistService $artistRequest
  ) {
    $this->entityTypeManager = $entityTypeManager;
    $this->spotifyRequest = $spotifyRequest;
    $this->artistRequest = $artistRequest;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $ids): void {
    $response = $this->spotifyRequest->getResource('tracks', $ids);
    $response = json_decode($response->getBody()->getContents(), TRUE);

    foreach ($response['tracks'] as $song) {

      // Get the artist resource because the song endpoint don't return images.
      $artistEntity = $this->artistRequest->saveSingleArtist($song['artists'][0]['id']);
      if ($artistEntity) {
        $album = new Album($song['album'], $artistEntity);
        $albumEntity = $this->getEntity($album);
        $albumEntity = $album->save($albumEntity);

        $song = new Song($song, $artistEntity, $albumEntity);
        $songEntity = $this->getEntity($song);
        $songEntity = $song->save($songEntity);
      }
    }
  }

}
