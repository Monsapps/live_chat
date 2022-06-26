<?php

namespace Drupal\live_chat\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ChatForm extends ConfigFormBase
{

    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $config = $this->config('live_chat.settings');

        $form['live_chat_status'] = [  
            '#type' => 'checkbox',  
            '#title' => $this->t('Enable live chat'),  
            '#description' => $this->t('Enable / disable the Live Chat module'),  
            '#default_value' => $config->get('live_chat_status')
        ];

        $form['live_chat_timeout'] = [  
            '#type' => 'select',  
            '#title' => $this->t('Live chat timeout'),  
            '#description' => $this->t('The time when users need to reconnect'),
            '#default_value' => $config->get('live_chat_timeout'),
            '#options' => [
                '0' => $this->t('Unlimited'),
                '5' => $this->t('Five minutes'),
                '10' => $this->t('Ten minutes'),
                '15' => $this->t('Fiftheen minutes')
              ]
        ];

        return parent::buildForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        parent::submitForm($form, $form_state);

        $boolean = $form_state->getValue('live_chat_status') === 0 ? false : true; 

        $this->config('live_chat.settings')
            ->set('live_chat_status', $boolean)
            ->save();
    }

    public function getFormId(): string
    {
        return 'live_chat.form';
    }

    protected function getEditableConfigNames(): array
    {
        return [
            'live_chat.settings'
        ];
    }
}
