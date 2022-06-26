<?php

namespace Drupal\Tests\live_chat\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

class LiveChatActionsTest extends WebDriverTestBase
{
    protected static $modules = array('live_chat');
    protected $defaultTheme = 'stark';

    public function testIsFormMessageIsUp(): void
    {
        $account = $this->drupalCreateUser(['view message entity']);

        $this->drupalLogin($account);

        $this->drupalGet('/chat');

        $web_assert = $this->assertSession();

        $page = $this->getSession()->getPage();

        $web_assert->fieldExists('message');

        $messageInput = $page->findById('edit-message');

        $submitButton = $page->findById('edit-submit');

        $this->assertNotEmpty($messageInput);
        $this->assertNotEmpty($submitButton);

        $messageInput->setValue('Test');

        $submitButton->click();

        //TODO add proper DOM for messages
    }
}
