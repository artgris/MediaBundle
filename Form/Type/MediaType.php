<?php

namespace Artgris\Bundle\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars = array_replace($view->vars, [
            'conf' => $options['conf'],
            'tree' => $options['tree'],
            'readonly' => $options['readonly'],
            'allow_crop' => $options['allow_crop'],
            'crop_options' => $options['crop_options'],
            'display_file_manager' => $options['display_file_manager'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'by_reference' => false,
            'allow_crop' => true,
            'crop_options' => [
                'display_crop_data' => true,
                'allow_flip' => true,
                'allow_rotation' => true,
                'ratio' => false,
            ],
            'readonly' => false,
            'tree' => 0,
            'error_bubbling' => false,
            'display_file_manager' => true,
        ]);

        $resolver->setRequired('conf');
    }

    public function getBlockPrefix()
    {
        return 'artgris_media';
    }

    public function getParent()
    {
        return TextType::class;
    }
}
