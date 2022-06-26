<?php

namespace Drupal\live_chat\Service;

use Drupal\Core\Config\ConfigFactoryInterface;

class LiveChatService
{

    protected ConfigFactoryInterface $configFactory;

    public function __construct(ConfigFactoryInterface $configFactory)
    {
        $this->configFactory = $configFactory;
    }

    public function isEnabled(): bool
    {
        $config = $this->configFactory->getEditable('live_chat.settings');

        if($config->get('live_chat_status')) {
            return true;
        }

        return $config->get('live_chat_status');
    }

    public function getTimeout(): int
    {
        $config = $this->configFactory->getEditable('live_chat.settings');

        if($config->get('live_chat_timeout') == NULL) {
            return 0;
        }
        
        return $config->get('live_chat_timeout');
    }
}
