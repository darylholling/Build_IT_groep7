<?php

namespace App\Form;

use App\Entity\Arduino;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArduinoType
 */
class ArduinoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', TextType::class, [
                'label' => 'Url',
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT  , static function (FormEvent $event) {
            /** @var Arduino $arduino */
            $arduino = $event->getData();

            $arduino->setActive(true);
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Arduino::class
        ]);
    }
}