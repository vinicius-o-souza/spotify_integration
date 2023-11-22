<?php

namespace Drupal\spotify_integration\Service;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\State\State;
use Drupal\key\KeyRepository;
use Drupal\spotify_integration\Form\SpotifySettingsForm;

/**
 * Class to handle the configurations variables.
 */
class SpotifyConfigService {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The key module repository service.
   *
   * @var \Drupal\key\KeyRepository
   */
  protected $keyRepository;

  /**
   * The core state service.
   *
   * @var \Drupal\Core\State\State
   */
  protected $state;

  /**
   * Constructs a SpotifyConfigService object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory service.
   * @param \Drupal\key\KeyRepository $keyRepository
   *   The key module repository service.
   * @param \Drupal\Core\State\State $state
   *   The core state service.
   */
  public function __construct(
    ConfigFactoryInterface $configFactory,
    KeyRepository $keyRepository,
    State $state
  ) {
    $this->configFactory = $configFactory;
    $this->keyRepository = $keyRepository;
    $this->state = $state;
  }

  /**
   * Get the spotify url.
   *
   * @return string
   *   The spotify url.
   */
  public function getSpotifyUrl(): string {
    return $this->getConfigObject()->get('spotify_url');
  }

  /**
   * Get the spotify client id.
   *
   * @return string
   *   The spotify client id.
   */
  public function getClientId(): string {
    $values = $this->keyRepository->getKey('spotify')->getKeyValues();
    return $values['spotify_client_id'] ?? '';
  }

  /**
   * Get the spotify client secret.
   *
   * @return string
   *   The spotify client secret.
   */
  public function getClientSecret(): string {
    $values = $this->keyRepository->getKey('spotify')->getKeyValues();
    return $values['spotify_client_secret'] ?? '';
  }

  /**
   * Get the spotify token.
   *
   * @return string
   *   The spotify token.
   */
  public function getToken(): string {
    return $this->state->get('spotify_token', '');
  }

  /**
   * Save the Spotify Token into the configurations.
   */
  public function saveToken(string $token): void {
    $this->state->set('spotify_token', $token);
  }

  /**
   * Get the Spotify config object.
   *
   * @return \Drupal\Core\Config\Config
   *   The config object.
   */
  private function getConfigObject(): Config {
    return $this->configFactory->getEditable(SpotifySettingsForm::CONFIG_NAME);
  }

}
