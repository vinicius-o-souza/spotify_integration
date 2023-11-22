<?php

namespace Drupal\spotify_integration\Model;

use Drupal\node\Entity\Node;

/**
 * Album model class.
 */
class Album extends Resource {

  /**
   * The artist of the song.
   *
   * @var \Drupal\node\Entity\Node
   */
  public $artist;

  /**
   * The url of the album picture.
   *
   * @var string
   */
  public $picture;

  /**
   * The release date of the album.
   *
   * @var string
   */
  public $releaseDate;

  /**
   * Construct an Album object.
   *
   * @param array $spotifyAlbum
   *   The array with spotify album informations.
   * @param \Drupal\node\Entity\Node $artist
   *   The artist entity object.
   */
  public function __construct(array $spotifyAlbum, Node $artist) {
    parent::__construct($spotifyAlbum['id'], $spotifyAlbum['name']);
    $this->artist = $artist;
    $this->picture = $spotifyAlbum['images'][0]['url'] ?? '';
    $this->releaseDate = $spotifyAlbum['release_date'];
  }

  /**
   * {@inheritdoc}
   */
  public function save(Node $entity): Node {
    $entity->set('title', $this->name);
    $entity->set('field_release_date', $this->releaseDate);
    $entity->set('field_picture', $this->picture);
    $entity->set('field_artist', $this->artist);
    $entity->save();

    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getBundle(): string {
    return 'album';
  }

}
