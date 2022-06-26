<?php

namespace Drupal\live_chat\Entity;

use Drupal\Core\Entity\ContentEntityBase;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\UserInterface;

 /**
 * @ingroup live_chat
 * @ContentEntityType(
 *   id = "live_chat_message",
 *   label = @Translation("Message entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\live_chat\MessageAccessControlHandler",
 *   },
 *   base_table = "chat_live_message",
 *   admin_permission = "administer message entity",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   }
 * )
 */
class Message extends ContentEntityBase implements MessageInterface
{
    public function preSave(EntityStorageInterface $storage)
    {
        $this->setOwnerId(\Drupal::currentUser()->id());
    }

    public function getCreatedTime() {
        return $this->get('created')->value;
    }


    public function getOwner() {
        return $this->get('user_id')->entity;
    }

    public function getOwnerId() {
        return $this->get('user_id')->target_id;
    }

    public function setOwnerId($uid) {
        $this->set('user_id', $uid);
        return $this;
    }

    public function setOwner(UserInterface $account) {
        $this->set('user_id', $account->id());
        return $this;
    }

    public function getMessage(): string
    {
        return $this->get('content')->value;
    }

    public function setMessage(string $content): self
    {
        $this->set('content', $content);
        return $this;
    }

    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {
        // Standard field, used as unique if primary index.
        $fields['id'] = BaseFieldDefinition::create('integer')
        ->setLabel('ID')
        ->setDescription('The ID of the Message entity.')
        ->setReadOnly(TRUE);

        // Standard field, unique outside of the scope of the current project.
        $fields['uuid'] = BaseFieldDefinition::create('uuid')
            ->setLabel('UUID')
            ->setDescription('The UUID of the Message entity.')
            ->setReadOnly(TRUE);

        $fields['created'] = BaseFieldDefinition::create('created');

        $fields['status'] = BaseFieldDefinition::create('boolean');
        
        $fields['content'] = BaseFieldDefinition::create('string')
            ->setLabel('Message')
            ->setDescription('The content message.')
            ->setDefaultValue([
                'default_value' => '',
                'max_lenght' => 255,
                'text_processing' => 0
            ])
            ->setDisplayOptions('view', array(
                'label' => 'above',
                'type' => 'string',
                'weight' => -6,
              ))
              ->setDisplayOptions('form', array(
                'type' => 'string_textfield',
                'weight' => -6,
              ))
              ->setDisplayConfigurable('form', TRUE)
              ->setDisplayConfigurable('view', TRUE);

        /*$fields['user_id'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel('User ID')
            ->setDescription('Author ID.')
            ->setSetting('target_type', 'user');*/
        
            $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel('User Name')
            ->setDescription('The Name of the associated user.')
            ->setSetting('target_type', 'user')
            ->setSetting('handler', 'default')
            ->setDisplayOptions('view', array(
                'label' => 'above',
                'type' => 'entity_reference_label',
                'weight' => -3,
            ))
            ->setDisplayConfigurable('view', TRUE);

        return $fields;
    }
}
