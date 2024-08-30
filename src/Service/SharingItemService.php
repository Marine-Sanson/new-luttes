<?php

namespace App\Service;

use DateInterval;
use DateTimeZone;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\ShareAnswer;
use App\Entity\SharingItem;
use App\Entity\SharingCategory;
use App\Repository\ShareAnswerRepository;
use App\Repository\SharingItemRepository;
use App\Repository\SharingCategoryRepository;



class SharingItemService
{
    public function __construct(
        private readonly SharingItemRepository $sharingItemRepository,
        private readonly SharingCategoryRepository $sharingCategoryRepository,
        private readonly ShareAnswerRepository $shareAnswerRepository,
    ) {

    }

    public function manageSharingItem(User $user, SharingCategory $category, string $title, string $content)
    {
        $sharingItem = (new SharingItem())
            ->setUser($user)
            ->setTitle($title)
            ->setContent($content)
            ->setCategory($category)
            ->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")))
            ;

        $this->sharingItemRepository->saveSharingItem($sharingItem);

    }

    public function manageNewShareAnswer(int $sharingItemId, User $user, string $content)
    {
        $shareAnswer = (new ShareAnswer())
            ->setUser($user)
            ->setSharingItem($this->sharingItemRepository->findOneById($sharingItemId))
            ->setContent($content)
            ->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")))
        ;

        $this->shareAnswerRepository->saveShareAnswer($shareAnswer);

    }

    public function getSharingItemById(int $id): SharingItem
    {
        return $this->sharingItemRepository->findOneById($id);
    }

    public function getSharingCategory(int $catId): SharingCategory
    {
        return $this->sharingCategoryRepository->findOneById($catId);
    }

    public function getSharingItemsByCat(int $catId): array
    {
        return $this->sharingItemRepository->findByCategory($this->sharingCategoryRepository->findOneById($catId));
    }

    public function getSharingItems(): array
    {
        return $this->sharingItemRepository->findAll();
    }

    public function getSharingCategories(): array
    {
        return $this->sharingCategoryRepository->findAll();
    }

}
