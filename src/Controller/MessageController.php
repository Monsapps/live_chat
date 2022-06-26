<?php
/**
 * Message API
 */
namespace Drupal\live_chat\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\live_chat\Service\LiveChatService;
use Drupal\live_chat\Service\MessageService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MessageController extends ControllerBase
{

    public function addMessage(Request $request): JsonResponse
    {
        if(\Drupal::csrfToken($request->request->get('form_token'))) {

            $messageService = \Drupal::getContainer()->get(MessageService::class);

            if($messageService->addMessage(\Drupal::currentUser()->id(), $request->request->get('message'))) {

                return new JsonResponse(['success' => 'Created.'], 201);

            }

            return new JsonResponse(['error' => 'Database not available.'], 400);
        }
        return new JsonResponse(['error' => 'Access denied.'], 403);
    }

    /**
     * Get messages
     * @return StreamedResponse content of new messages
     */
    public function getMessages(): StreamedResponse
    {

        $liveChatService = \Drupal::getContainer()->get(LiveChatService::class);

        $messageService = \Drupal::getContainer()->get(MessageService::class);

        // Define timeout of php script (default = 0 Unlimited)
        set_time_limit($liveChatService->getTimeout() * 60);

        $headers = [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive'
        ];

        // Store last Id message senedd
        $lastId = null;

        $callable = function () use ($lastId, $messageService) {

            while(true) {

                $messagesArray = $messageService->getMessages($lastId);

                /**
                 * When no new messages $messagesArray['last_id'] is null,
                 * so we override the $lastId when needed
                 */
                if(!is_null($messagesArray['last_id'])) {
                    $lastId = $messagesArray['last_id'];
                }

                $jsonOut = $messagesArray['data'];

                echo "data: {$jsonOut}\n\n";

                ob_end_flush();
                flush();

                if (connection_aborted()) break;
            
                sleep(1);
            }
        };
        
        return new StreamedResponse($callable, 200, $headers);
    }
}