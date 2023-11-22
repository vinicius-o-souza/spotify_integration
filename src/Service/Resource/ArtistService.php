<?php

namespace Drupal\spotify_integration\Service\Resource;

use Drupal\node\Entity\Node;
use Drupal\spotify_integration\Model\Album;
use Drupal\spotify_integration\Model\Artist;
use Drupal\spotify_integration\Model\Song;

/**
 * Request artists information from the Spotify APIs.
 */
class ArtistService extends ResourceService {

  /**
   * {@inheritdoc}
   */
  public function save(array $ids): void {
    $response = $this->spotifyRequest->getResource('artists', $ids);
    $response = json_decode($response->getBody()->getContents(), TRUE);
    foreach ($response['artists'] as $artist) {
      $artist = new Artist($artist);
      $genres = $this->getGenresIds($artist->genres);
      $artist->setGenres($genres);
      $artistEntity = $this->getEntity($artist);
      $artistEntity = $artist->save($artistEntity);

      $songs = $this->getTopSongs($artistEntity);

      $topSongs = array_slice($songs, 0, 10);
      $artistEntity->set('field_top_songs', $topSongs);
      $artistEntity->save();
    }
  }

  /**
   * Save single artist.
   *
   * @param string $artistId
   *   The spotify id of the artist.
   *
   * @return \Drupal\node\Entity\Node|null
   *   The artist entity.
   */
  public function saveSingleArtist(string $artistId): ?Node {
    $response = $this->spotifyRequest->getResource('artists', [$artistId]);
    $response = json_decode($response->getBody()->getContents(), TRUE);

    $artistEntity = NULL;
    foreach ($response['artists'] as $artist) {
      $artist = new Artist($artist);
      $genres = $this->getGenresIds($artist->genres);
      $artist->setGenres($genres);
      $artistEntity = $this->getEntity($artist);
      $artistEntity = $artist->save($artistEntity);
    }

    return $artistEntity;
  }

  /**
   * Get the artist songs.
   *
   * @param \Drupal\node\Entity\Node $artist
   *   The artist entity.
   *
   * @return array
   *   An array with the artist songs.
   */
  private function getTopSongs(Node $artist): array {
    $artistId = $artist->get('field_spotify_id')->getString();
    $path = 'artists/' . $artistId . '/top-tracks';
    $response = $this->spotifyRequest->getRequest($path, [
      'query' => [
        'market' => 'ES',
      ],
    ]);
    $response = json_decode($response->getBody()->getContents(), TRUE);
    $songs = [];
    foreach ($response['tracks'] as $song) {

      $album = new Album($song['album'], $artist);
      $albumEntity = $this->getEntity($album);
      $albumEntity = $album->save($albumEntity);

      $song = new Song($song, $artist, $albumEntity);
      $songEntity = $this->getEntity($song);
      $songEntity = $song->save($songEntity);

      $songs[] = $songEntity;
    }

    return $songs;
  }

}
