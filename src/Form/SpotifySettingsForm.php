<?php

namespace Drupal\spotify_integration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Spotify Integration settings for this site.
 */
class SpotifySettingsForm extends ConfigFormBase {

  /**
   * The config machine name.
   *
   * @var string
   */
  public const CONFIG_NAME = 'spotify_integration.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'spotify_integration_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [self::CONFIG_NAME];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['instructions'] = [
      '#type' => 'markup',
      '#markup' => '
        <p>In order to the integration works correctly we have to inform the CLIENT ID and CLIENT SECRET keys</p>
        <p>To generate the keys please follow the instructions here
          <a href="https://developer.spotify.com/documentation/web-api/tutorials/getting-started">Spotify Develop Instructions</a>
        </p>
        <p>To add or edit the Spotify Authentication Keys go to the spotify.key file in the root directory</p>
      ',
    ];

    $form['spotify_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Spotify Web API URL'),
      '#description' => $this->t('The URL of the Spotify Web API'),
      '#default_value' => $this->config('spotify_integration.settings')->get('spotify_url'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('spotify_integration.settings')
      ->set('spotify_url', $form_state->getValue('spotify_url'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
