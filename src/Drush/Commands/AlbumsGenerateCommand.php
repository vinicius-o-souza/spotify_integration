<?php

namespace Drupal\spotify_integration\Drush\Commands;

use Drupal\spotify_integration\Service\Resource\AlbumService;
use Drush\Commands\DrushCommands;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The albums generate command file.
 */
class AlbumsGenerateCommand extends DrushCommands {

  /**
   * The spotify_integration album request service.
   *
   * @var \Drupal\spotify_integration\Service\Resource\AlbumService
   */
  protected $albumRequest;

  /**
   * Constructs a AlbumsGenerateCommand object.
   *
   * @param \Drupal\spotify_integration\Service\Resource\AlbumService $albumRequest
   *   The spotify_integration album request service.
   */
  public function __construct(AlbumService $albumRequest) {
    $this->albumRequest = $albumRequest;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new static(
      $container->get('spotify_integration.albums'),
    );
  }

  /**
   * Command to generate content from the Spotify API.
   *
   * @command spotify_integration:albums
   *
   * @usage drush spotify_integration:albums
   *   Usage description
   *
   * @aliases sialbums
   */
  public function generateAlbums(): void {
    $ids = [
      '382ObEPsp2rxGrnsizN5TX',
      '1A2GTWGtFfWp7KSQTwWOyo',
      '2noRn2Aes5aoNVsU6iWThc',
      '2up3OPMp9Tb4dAKM2erWXQ',
      '0D49RvtlLCKyxeDKDnBU2R',
    ];

    $this->albumRequest->save($ids);

    $this->logger()->success('Albums generated successfully');
  }

}
