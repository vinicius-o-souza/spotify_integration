<?php

namespace Drupal\spotify_integration\Model;

use Drupal\node\Entity\Node;

/**
 * Resource model class.
 */
abstract class Resource {

  /**
   * The name of the resource.
   *
   * @var string
   */
  public $name;

  /**
   * The spotify id.
   *
   * @var string
   */
  public $spotifyId;

  /**
   * Construct an Resource object.
   *
   * @param string $spotifyId
   *   The spotify id string.
   * @param string $name
   *   The name of the resource.
   */
  public function __construct(string $spotifyId, string $name) {
    $this->spotifyId = $spotifyId;
    $this->name = $name;
  }

  /**
   * Save the entity.
   *
   * @param \Drupal\node\Entity\Node $entity
   *   The entity object to save.
   *
   * @return \Drupal\node\Entity\Node
   *   The entity.
   */
  abstract public function save(Node $entity): Node;

  /**
   * Bundle of the model class.
   *
   * @return string
   *   The bundle string.
   */
  abstract public function getBundle(): string;

}
