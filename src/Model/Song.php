<?php

namespace Drupal\spotify_integration\Model;

use Drupal\node\Entity\Node;

/**
 * Song model class.
 */
class Song extends Resource {

  /**
   * The album of the song.
   *
   * @var \Drupal\node\Entity\Node
   */
  public $album;

  /**
   * The artist of the song.
   *
   * @var \Drupal\node\Entity\Node
   */
  public $artist;

  /**
   * The url of the picture of the song.
   *
   * @var string
   */
  public $picture;

  /**
   * The popularity of the song.
   *
   * @var int
   */
  public $popularity;

  /**
   * Construct an Song object.
   *
   * @param array $spotifySong
   *   The array with spotify song informations.
   * @param \Drupal\node\Entity\Node $artist
   *   The artist entity object.
   * @param \Drupal\node\Entity\Node $album
   *   The album entity object.
   */
  public function __construct(array $spotifySong, Node $artist, Node $album) {
    parent::__construct($spotifySong['id'], $spotifySong['name']);
    $this->album = $album;
    $this->artist = $artist;
    $this->picture = $spotifySong['album']['images'][0]['url'] ?? '';
    $this->popularity = $spotifySong['popularity'] ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function save(Node $entity): Node {
    $entity->set('title', $this->name);
    $entity->set('field_popularity', $this->popularity);
    $entity->set('field_artist', $this->artist);
    $entity->set('field_picture', $this->picture);
    $entity->set('field_album', $this->album);
    $entity->save();

    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getBundle(): string {
    return 'song';
  }

  /**
   * Set the picture url field.
   *
   * @param string $picture
   *   The picture URL string.
   */
  public function setPicture(string $picture): void {
    $this->picture = $picture;
  }

}
