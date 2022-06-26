<?php

namespace Drupal\live_chat\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\live_chat\Form\MessageForm;
use Drupal\live_chat\Service\LiveChatService;

class DefaultController extends ControllerBase
{
  /**
   * Return the main chat room
   * 
   * @return array a renderable array
   */
  public function mainRoom(): array
  {

    $liveChatService = \Drupal::getContainer()->get(LiveChatService::class);

    if(!$liveChatService->isEnabled()) {
      return [
        '#title' => 'Main chat room',
        '#markup' => 'Désactiver'
      ];
    }

    $form = $this->formBuilder()->getForm(MessageForm::class);

    return  [
      '#theme' => 'my_template',
      '#test_var' => $this->t('Test Value'),
      '#form' => $form
    ];
  }
}
