<?php

namespace Artgris\Bundle\MediaBundle\Form\Type;

use Artgris\Bundle\MediaBundle\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'artgris.media.path.placeholder', 'readonly' => $options['path_readonly']],
            ])
            ->add('alt', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'artgris.media.alt.placeholder']
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars = array_replace($view->vars, [
            'conf' => $options['conf'],
            'tree' => $options['tree'],
            'allow_alt' => $options['allow_alt'],
            'allow_crop' => $options['allow_crop'],
            'crop_options' => $options['crop_options'],
            'display_file_manager' => $options['display_file_manager'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'data_class' => Media::class,
            'by_reference' => false,
            'allow_alt' => false,
            'allow_crop' => true,
            'crop_options' => [
                'display_crop_data' => true,
                'allow_flip' => true,
                'allow_rotation' => true,
                'ratio' => 16/9
            ],
            'path_readonly' => false,
            'conf' => false,
            'tree' => 0,
            'error_bubbling' => false,
            'display_file_manager' => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'artgris_media';
    }

}
