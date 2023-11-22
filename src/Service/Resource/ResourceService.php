<?php

namespace Drupal\spotify_integration\Service\Resource;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;
use Drupal\spotify_integration\Model\Resource;
use Drupal\spotify_integration\Service\SpotifyRequest;

/**
 * Request resources information from the Spotify APIs.
 */
abstract class ResourceService implements ResourceInterface {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The spotify_integration request service.
   *
   * @var \Drupal\spotify_integration\Service\SpotifyRequest
   */
  protected $spotifyRequest;

  /**
   * Constructs a Request object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   * @param \Drupal\spotify_integration\Service\SpotifyRequest $spotifyRequest
   *   The spotify_integration request service.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, SpotifyRequest $spotifyRequest) {
    $this->entityTypeManager = $entityTypeManager;
    $this->spotifyRequest = $spotifyRequest;
  }

  /**
   * Get the entity.
   *
   * @param \Drupal\spotify_integration\Model\Resource $resource
   *   The resource object.
   *
   * @return \Drupal\node\Entity\Node
   *   The entity object.
   */
  protected function getEntity(Resource $resource): Node {
    $storage = $this->entityTypeManager->getStorage('node');
    $entity = $storage->loadByProperties(['field_spotify_id' => $resource->spotifyId]);
    $entity = array_shift($entity);

    if (!$entity) {
      /** @var \Drupal\node\Entity\Node $entity */
      $entity = $storage->create([
        'type' => $resource->getBundle(),
        'field_spotify_id' => $resource->spotifyId,
      ]);
    }

    return $entity;
  }

  /**
   * Get the ids of the resource genres.
   *
   * @param array $resourceGenres
   *   An array with all genres from the resource.
   *
   * @return array
   *   The ids of the genre terms.
   */
  protected function getGenresIds(array $resourceGenres): array {
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $genres = $storage->loadByProperties(['vid' => 'genres']);
    $resourceGenresIds = [];

    foreach ($genres as $genre) {
      $key = array_search($genre->label(), $resourceGenres);
      if ($key !== FALSE) {
        unset($resourceGenres[$key]);
        $resourceGenresIds[] = $genre->id();
      }
    }

    foreach ($resourceGenres as $genre) {
      $term = $storage->create([
        'vid' => 'genres',
        'name' => $genre,
      ]);
      $term->save();
      $resourceGenresIds[] = $term->id();
    }

    return $resourceGenresIds;
  }

}
