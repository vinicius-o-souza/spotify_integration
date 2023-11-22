<?php

namespace Drupal\spotify_integration\Drush\Commands;

use Drupal\spotify_integration\Service\Resource\ArtistService;
use Drush\Commands\DrushCommands;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The artist generate command file.
 */
class ArtistsGenerateCommand extends DrushCommands {

  /**
   * The spotify_integration artists request service.
   *
   * @var \Drupal\spotify_integration\Service\Resource\ArtistService
   */
  protected $artistRequest;

  /**
   * Constructs a ArtistsGenerateCommand object.
   *
   * @param \Drupal\spotify_integration\Service\Resource\ArtistService $artistRequest
   *   The spotify_integration artists request service.
   */
  public function __construct(ArtistService $artistRequest) {
    $this->artistRequest = $artistRequest;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new static(
      $container->get('spotify_integration.artists'),
    );
  }

  /**
   * Command to generate content from the Spotify API.
   *
   * @command spotify_integration:artists
   *
   * @usage drush spotify_integration:artists
   *   Usage description
   *
   * @aliases siartists
   */
  public function generateContent(): void {
    $ids = [
      '2CIMQHirSU0MQqyYHq0eOx',
      '57dN52uHvrHOxijzpIgu3E',
      '1vCWHaC5f2uS3yhpwWbIA6',
      '0TnOYISbd1XYRBk9myaseg',
      '3sgFRtyBnxXD5ESfmbK4dl',
    ];

    $this->artistRequest->save($ids);

    $this->logger()->success('Artists generated successfully');
  }

}
