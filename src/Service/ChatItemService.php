<?php

namespace App\Service;

use DateInterval;
use DateTimeZone;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\ChatItem;
use App\Entity\ChatAnswer;
use App\Repository\ChatItemRepository;


class ChatItemService
{
    public function __construct(
        private readonly ChatItemRepository $chatItemRepository
    ) {

    }

    public function manageNewChatItem($title, $message, $user)
    {
        $chatItem = (new ChatItem())
            ->setUser($user)
            ->setTitle($title)
            ->setMessage($message)
            ->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")))
        ;

        $this->chatItemRepository->saveChatItem($chatItem);
    }

    public function manageNewChatAnswer(int $chatItemId, User $user, string $content)
    {

        $chatAnswer = (new ChatAnswer())
            ->setChatItem($this->chatItemRepository->findOneById($chatItemId))
            ->setUser($user)
            ->setContent($content)
            ->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")))
        ;

        $this->chatItemRepository->saveChatAnswer($chatAnswer);
    }

    public function getOldChatItems(): ?array
    {
        $now = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
        $oldDate = $now->sub(new DateInterval('P4M'));

        return $this->chatItemRepository->findOldChatItems($oldDate);
    }

    public function deleteOldChatItems(array $oldChatItems): void
    {
        array_map(
            function (ChatItem $oldChatItem) {
                $chatAnswers = $oldChatItem->getChatAnswers();
                foreach($chatAnswers as $chatAnswer){
                    $this->chatItemRepository->deleteChatAnswer($chatAnswer);
                }
                return $this->chatItemRepository->deleteChatItem($oldChatItem);
            },
            $oldChatItems
        );
    }

    public function getChatItems(): array
    {
        return $this->chatItemRepository->findAllChatItems();
    }

    public function getChatItem($chatItemId): ChatItem
    {
        return $this->chatItemRepository->findOneById($chatItemId);
    }

}
