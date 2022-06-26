<?php

namespace Drupal\live_chat;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

class MessageAccessControlHandler extends EntityAccessControlHandler
{
    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
    {
        switch ($operation) {
            case 'view':
              return AccessResult::allowedIfHasPermission($account, 'view message entity');
      
            case 'edit':
              return AccessResult::allowedIfHasPermission($account, 'edit message entity');
          }
          return AccessResult::allowed();
    }

    protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL)
    {
        return AccessResult::allowedIfHasPermission($account, 'add message entity');
    }
}
