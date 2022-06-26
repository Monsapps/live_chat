<?php

namespace Drupal\Tests\live_chat\Functionnal;

use Drupal\Tests\BrowserTestBase;

class LiveChatTest extends BrowserTestBase
{
    /**
     * Modules to install.
     *
     * @var array
     */
    protected static $modules = array('live_chat');

    /**
     * Theme to enable.
     *
     * @var string
     */
    protected $defaultTheme = 'stark';

    public function testMainRoomSetupIsUp()
    {
        $account = $this->drupalCreateUser(['administer message entity']);
        $this->drupalLogin($account);

        $this->drupalGet('/admin/config/system/live-chat');

        $this->assertSession()->statusCodeEquals(200);
    }

    public function testMainRoomIsUp()
    {
        $account = $this->drupalCreateUser(['view message entity']);
        $this->drupalLogin($account);

        $this->drupalGet('/chat');

        $this->assertSession()->statusCodeEquals(200);
    }
}