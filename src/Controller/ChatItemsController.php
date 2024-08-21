<?php

namespace App\Controller;

use App\Entity\ChatItem;
use App\Entity\ChatAnswer;
use App\Form\ChatItemType;
use App\Form\ChatAnswerType;
use App\Service\ChatItemService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatItemsController extends AbstractController
{
    public function __construct(private readonly ChatItemService $chatItemService){

    }

    #[Route('/messages', name: 'app_chatItems')]
    public function displayChatItems(): Response
    {
        $chatItems = $this->chatItemService->getChatItems();

        return $this->render('chatItems/chatItems.html.twig', [
            "chatItems" => $chatItems,
        ]);
    }

    #[Route('/messages/nouveau', name: 'app_newChatItem')]
    public function addChatItem(Request $request): Response
    {
        $chatItem = new ChatItem();
        $chatItemForm = $this->createForm(ChatItemType::class, $chatItem);
        
        $chatItemForm->handleRequest($request);

        if ($chatItemForm->isSubmitted() && $chatItemForm->isValid()) {
            $user = $this->getUser();

            $this->chatItemService->manageNewChatItem($chatItemForm->get('title')->getData(), $chatItemForm->get('message')->getData(), $user);

            $this->addFlash('success', 'Ton message a été enregistré');
            return $this->redirectToRoute('app_chatItems');
            
        }

        return $this->render('chatItems/addChatItem.html.twig', [
            "chatItemForm" => $chatItemForm,
        ]);
    }

    #[Route('/messages/{chatItemId}/repondre', name: 'app_newChatAnswer')]
    public function addChatAnswer(Request $request, int $chatItemId): Response
    {
        $chatAnswer = new ChatAnswer();
        $chatAnswerForm = $this->createForm(ChatAnswerType::class, $chatAnswer);
        
        $chatAnswerForm->handleRequest($request);

        if ($chatAnswerForm->isSubmitted() && $chatAnswerForm->isValid()) {
            $user = $this->getUser();

            $this->chatItemService->manageNewChatAnswer($chatItemId, $user, $chatAnswerForm->get('content')->getData());

            $this->addFlash('success', 'Ta réponse a été enregistrée');
            return $this->redirectToRoute('app_chatItems');
        }

        $chatItem = $this->chatItemService->getChatItem($chatItemId);

        return $this->render('chatItems/addChatAnswer.html.twig', [
            "chatAnswerForm" => $chatAnswerForm,
            "chatItem" => $chatItem,
        ]);
    }

    #[Route('/messages/supprimer', name: 'app_deleteOldChatItem')]
    public function deleteChatItems(): Response
    {
        $oldChatItems = $this->chatItemService->getOldChatItems();

        if($oldChatItems){
            $this->chatItemService->deleteOldChatItems($oldChatItems);

            $this->addFlash('success', 'Les messages anciens ont bien étés supprimées');
            return $this->redirectToRoute('app_chatItems');
        }

        $this->addFlash('warning', 'Il n\'y a aucun message assez ancien');
        return $this->redirectToRoute('app_chatItems');

    }
}
