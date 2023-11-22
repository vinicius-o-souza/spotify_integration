<?php

namespace Drupal\spotify_integration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\spotify_integration\Service\Resource\ResourceFactoryService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Spotify Integration form.
 */
class GenerateContentForm extends FormBase {

  /**
   * The resource factory service.
   *
   * @var \Drupal\spotify_integration\Service\Resource\ResourceFactoryService
   */
  protected $resourceFactoryService;

  /**
   * Constructs a GenerateContentForm object.
   *
   * @param \Drupal\spotify_integration\Service\Resource\ResourceFactoryService $resourceFactoryService
   *   The resource factory service.
   */
  public function __construct(ResourceFactoryService $resourceFactoryService) {
    $this->resourceFactoryService = $resourceFactoryService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('spotify_integration.resource_factory'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'spotify_integration_generate_content_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['spotify_resource'] = [
      '#type' => 'select',
      '#title' => $this->t('Resource'),
      '#required' => TRUE,
      '#options' => [
        'album' => 'Album',
        'artist' => 'Artist',
        'song' => 'Song',
      ],
    ];

    $form['spotify_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Spotify ID'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $resource = $form_state->getValue('spotify_resource');
    $spotifyId = $form_state->getValue('spotify_id');

    $resourceService = $this->resourceFactoryService->getResourceService($resource);
    $resourceService->save([$spotifyId]);

    $this->messenger()->addStatus($this->t('The resource was created sucessfully.'));
  }

}
