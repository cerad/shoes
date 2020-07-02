<?php

namespace App\Controller;

use App\Repository\ShoeStoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShoeStoreController extends AbstractController
{
    private $shoeStoryRepository;
    public function __construct(ShoeStoreRepository $shoeStoreRepository)
    {
        $this->shoeStoryRepository = $shoeStoreRepository;
    }
    /**
     * @Route("/shoe-store/list/{storeCode}", name="shoe-store_list")
     */
    public function list(Request $request, $storeCode = 'ALL')
    {
        $storeCode = strtoupper($storeCode);

        $storeCodes = $this->shoeStoryRepository->findAllStoreCodes();
        $storeCodes = array_merge(['ALL' => 'ALL'],$storeCodes);

        $formData = ['storeCode' => $storeCode];

        $form = $this->createFormBuilder($formData)
            ->add('storeCode', ChoiceType::class,['choices' => $storeCodes])
            ->add('save', SubmitType::class, ['label' => 'List Store'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            return $this->redirectToRoute('shoe-store_list',$formData);
        }
        if ($storeCode === 'ALL') {
            $shoeStores = $this->shoeStoryRepository->findAllSortedByStore();
        }
        else {
            $shoeStores = $this->shoeStoryRepository->findAllForStore($storeCode);
        }
        return $this->render('shoe_store/list.html.twig', [
            'shoeStores' => $shoeStores,
            'form' => $form->createView(),
        ]);
    }
}
