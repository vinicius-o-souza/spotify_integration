<?php

namespace Drupal\spotify_integration\Model;

use Drupal\node\Entity\Node;

/**
 * Artist model class.
 */
class Artist extends Resource {

  /**
   * The number of followers.
   *
   * @var int
   */
  public $followers;

  /**
   * The genres of the artist.
   *
   * @var array
   */
  public $genres;

  /**
   * The url of the artist picture.
   *
   * @var string
   */
  public $picture;

  /**
   * Construct an Artist object.
   *
   * @param array $spotifyArtist
   *   The array with spotify artist informations.
   */
  public function __construct(array $spotifyArtist) {
    parent::__construct($spotifyArtist['id'], $spotifyArtist['name']);
    if (isset($spotifyArtist['genres'])) {
      $this->genres = array_map('strtoupper', $spotifyArtist['genres']);
    }
    $this->followers = $spotifyArtist['followers']['total'] ?? 0;
    $this->picture = $spotifyArtist['images'][0]['url'] ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function save(Node $entity): Node {
    $entity->set('title', $this->name);
    $entity->set('field_followers', $this->followers);
    $entity->set('field_genres', $this->genres);
    $entity->set('field_picture', $this->picture);
    $entity->save();

    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getBundle(): string {
    return 'artist';
  }

  /**
   * Set the genres array.
   *
   * @param array $genres
   *   The genres array.
   */
  public function setGenres(array $genres): void {
    $this->genres = $genres;
  }

}
