<?php declare(strict_types=1);

namespace Cerad\Common\Action;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;

trait FormTrait
{
    private FormFactoryInterface $formFactory;

    /** @required */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = isset($this->formFactory) ? $this->formFactory: $formFactory;
    }
    protected function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }
    protected function createFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        return $this->formFactory->createBuilder(FormType::class, $data, $options);
    }
}