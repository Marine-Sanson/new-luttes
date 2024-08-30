<?php

namespace App\Controller;

use App\Entity\ShareAnswer;
use App\Entity\SharingItem;
use App\Form\ShareAnswerType;
use App\Form\SharingItemType;
use App\Service\SharingItemService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/echanges')]
class SharingItemController extends AbstractController
{
    public function __construct(private readonly SharingItemService $sharingItemService){

    }

    #[Route('/', name: 'app_sharing_item')]
    public function displaySharingItems(): Response
    {
        $sharingItems = $this->sharingItemService->getSharingItems();
        $sharingCategories = $this->sharingItemService->getSharingCategories();

        return $this->render('sharing_item/sharing_item.html.twig', [
            "sharingItems" => $sharingItems,
            "sharingCategories" => $sharingCategories,
        ]);
    }

    #[Route('/par-categorie/{catId}', name: 'app_sharing_item_by_cat')]
    public function displaySharingItemsByCat(int $catId): Response
    {
        $category = $this->sharingItemService->getSharingCategory($catId);
        $sharingItems = $this->sharingItemService->getSharingItemsByCat($catId);
        $sharingCategories = $this->sharingItemService->getSharingCategories();

        return $this->render('sharing_item/sharing_item_by_cat.html.twig', [
            "sharingItems" => $sharingItems,
            "sharingCategories" => $sharingCategories,
            "category" => $category,
        ]);
    }

    #[Route('/nouveau/{catId}', name: 'add_new_sharing_item_by_cat')]
    public function addNewSharingItemByCat(Request $request, int $catId): Response
    {
        $category = $this->sharingItemService->getSharingCategory($catId);

        $sharingItem = new SharingItem();
        $sharingItemForm = $this->createForm(SharingItemType::class, $sharingItem);

        $sharingItemForm->handleRequest($request);

        if ($sharingItemForm->isSubmitted() && $sharingItemForm->isValid()) {
            $user = $this->getUser();

            $this->sharingItemService->manageSharingItem($user, $category, $sharingItemForm->get('title')->getData(), $sharingItemForm->get('content')->getData());

            $this->addFlash('success', 'Ton échange a été enregistré');
            return $this->redirectToRoute('app_sharing_item');
            
        }

        $sharingCategories = $this->sharingItemService->getSharingCategories();

        return $this->render('sharing_item/new_sharing_item.html.twig', [
            "sharingItemForm" => $sharingItemForm,
            "sharingCategories" => $sharingCategories,
            "category" => $category,
        ]);
    }

    #[Route('/{sharingItemId}/repondre', name: 'app_new_share_answer')]
    public function addShareAnswer(Request $request, int $sharingItemId): Response
    {
        $shareAnswer = new ShareAnswer();
        $shareAnswerForm = $this->createForm(ShareAnswerType::class, $shareAnswer);
        
        $shareAnswerForm->handleRequest($request);

        if ($shareAnswerForm->isSubmitted() && $shareAnswerForm->isValid()) {
            $user = $this->getUser();

            $this->sharingItemService->manageNewShareAnswer($sharingItemId, $user, $shareAnswerForm->get('content')->getData());

            $this->addFlash('success', 'Ta réponse a été enregistrée');
            return $this->redirectToRoute('app_sharing_item');
        }

        $sharingItem = $this->sharingItemService->getSharingItemById($sharingItemId);

        return $this->render('sharing_item/add_share_answer.html.twig', [
            "shareAnswerForm" => $shareAnswerForm,
            "sharingItem" => $sharingItem,
        ]);
    }

}
