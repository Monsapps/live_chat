<?php

namespace Drupal\live_chat\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * @ingroup live_chat
 */
interface MessageInterface extends ContentEntityInterface, EntityOwnerInterface
{
}
