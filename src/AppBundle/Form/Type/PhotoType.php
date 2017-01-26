<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => "Заголовок", 'required' => false,
            'attr' => array('class' => 'form-control')
        ));
        $builder->add('description', TextareaType::class, array(
            'label' => "Описание", 'required' => false,
            'attr' => array('class' => 'form-control photo_description')
        ));
        $builder->add('file', VichImageType::class, array(
            'label' => "Файл",
            'required' => false,
            'allow_delete' => false
        ));
        $builder->add('submit', SubmitType::class, array(
            'label' => "Подтвердить", 'attr' => array(
                'class' => 'btn btn-primary btn-lg'
            )
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Photo::class,
        ));
    }
}
