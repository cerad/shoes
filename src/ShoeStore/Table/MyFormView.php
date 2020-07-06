<?php declare(strict_types=1);

namespace App\ShoeStore\Table;

use Symfony\Component\Form\FormView;

class MyFormView
{
    protected FormView $formView;

    public function __construct(FormView $formView)
    {
        $this->formView = $formView;
    }
    public function render()
    {
        $formView = $this->formView;
        foreach($formView as $element) {
            dump($element);
        }
        $storeCodeFormView = $formView['storeCode'];

        //dump($storeCodeFormView);
    }
}