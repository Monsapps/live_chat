<?php

namespace Drupal\live_chat\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class MessageForm extends FormBase
{
    public function getFormId(): string
    {
        return 'live_chat.message_base';
    }

    public function buildForm(array $form, FormStateInterface $form_state): array
    {

        $route = Url::fromRoute('live_chat.form_message_add');

        $form['#action'] = $route->toString();

        $form['message'] = [
            '#type' => 'textfield'
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Send'),
          ];

        $form['#theme'] = 'add_message';

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state): void
    {
        
    }

    public function submitForm(array &$form, FormStateInterface $form_state): void
    {
        
    }
}