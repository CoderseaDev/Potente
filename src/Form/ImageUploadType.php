<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ImageUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,array(
                  'attr' => array('class' => 'form-control')))
            ->add('description', TextType::class, array(
                  'attr' => array('class' => 'form-control')))
            ->add('image', FileType::class, array(
                  'label'=>'Upload Image',
                  'attr' => array('class' => 'mt-3')))
            ->add('submit', SubmitType::class, array(
                'label' => 'save',
                'attr' => array('class' => 'btn btn-dark mt-3')));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}