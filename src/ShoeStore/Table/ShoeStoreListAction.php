<?php declare(strict_types=1);

namespace App\ShoeStore\Table;

use App\Repository\ShoeStoreRepository;
use Cerad\Common\Action\ActionInterface;
use Cerad\Common\Action\FormTrait;
use Cerad\Common\Action\RenderTrait;
use Cerad\Common\Action\RouterTrait;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShoeStoreListAction implements ActionInterface
{
    use FormTrait;
    use RenderTrait;
    use RouterTrait;

    public function __invoke(Request $request, ShoeStoreRepository $shoeStoreRepository, string $storeCode) : Response
    {
        $storeCode = strtoupper($storeCode);

        $storeCodes = $shoeStoreRepository->findAllStoreCodes();
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
            $shoeStores = $shoeStoreRepository->findAllSortedByStore();
        }
        else {
            $shoeStores = $shoeStoreRepository->findAllForStore($storeCode);
        }
        $formView = $form->createView();
        $myFormView = new MyFormView($formView);
        $myFormView->render();

        return $this->render('@ShoeStore/Table/list.html.twig', [
            'shoeStores' => $shoeStores,
            'form' => $formView,
        ]);
    }
}