<?php

namespace Drupal\spotify_integration\Drush\Commands;

use Drupal\spotify_integration\Service\Resource\SongService;
use Drush\Commands\DrushCommands;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The songs generate command file.
 */
class SongsGenerateCommand extends DrushCommands {

  /**
   * The spotify_integration songs request service.
   *
   * @var \Drupal\spotify_integration\Service\Resource\SongService
   */
  protected $songRequest;

  /**
   * Constructs a SongsGenerateCommand object.
   *
   * @param \Drupal\spotify_integration\Service\Resource\SongService $songRequest
   *   The spotify_integration songs request service.
   */
  public function __construct(SongService $songRequest) {
    $this->songRequest = $songRequest;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new static(
      $container->get('spotify_integration.songs'),
    );
  }

  /**
   * Command to generate content from the Spotify API.
   *
   * @command spotify_integration:songs
   *
   * @usage drush spotify_integration:songs
   *   Usage description
   *
   * @aliases sisongs
   */
  public function generateContent(): void {
    $ids = [
      '7ouMYWpwJ422jRcDASZB7P',
      '4VqPOruhp5EdPBeR92t6lQ',
      '2takcwOaAZWiXQijPHIx7B',
      '7K9axWRjzXSCcrp2Oxpk4b',
      '35qYi39MFWPLhZ0qCygprm',
    ];

    $this->songRequest->save($ids);

    $this->logger()->success('Songs generated successfully');
  }

}
