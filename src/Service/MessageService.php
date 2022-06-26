<?php
/**
 * Business code for Message Entity
 */
namespace Drupal\live_chat\Service;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\live_chat\Entity\Message;

class MessageService
{
    private int $count = 0;
    /**
     * Add message to database
     * @param int $userId id of user
     * @param string $message content of message
     * @return bool true if succeed
     */
    public function addMessage(int $userId, string $message): bool
    {
        $message = Message::create(
            [
                'status' => true,
                'content' => $message,
                'user_id' => $userId
            ]
            );
        try {
            $message->save();
            return true;
        } catch(EntityStorageException $exception) {
            return false;
        }
    }

    /**
     * Get message from database
     * 
     * @var int $lastId the last id of messages sended
     * @return array 
     *         array['last_id'] last ID queried
     *         array['data'] json of messages
     */
    public function getMessages(int $lastId = null): array
    {
        $query = \Drupal::entityQuery('live_chat_message')
                    ->condition('id', $lastId, '>')
                    ->sort('id', 'ASC');

        if(is_null($lastId)) {
            // Get last 10 messages
            $query = \Drupal::entityQuery('live_chat_message')
                ->sort('created', 'DESC')
                ->range(0, 10);
        }

        $messages = Message::loadMultiple($query->execute());

        if(is_null($lastId)) {
            // Reverse result to get order ASC
            $messages = array_reverse($messages);
        }

        $result = [];
        
        foreach($messages as $message) {
            $result[] = [
                'id' => $message->id->value,
                'content' => $message->content->value,
                'author' => $message->getOwner()->name->value 
            ];
        }

        // Send ID of last message
        $array['last_id'] = $message->id->value;

        $array['data'] = json_encode($result);

        return $array;
    }
}
