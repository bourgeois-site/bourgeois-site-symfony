<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => "Заголовок", 'required' => false,
            'attr' => array('class' => 'form-control')
        ));
        $builder->add('description', TextareaType::class, array(
            'label' => "Описание", 'required' => false,
            'attr' => array('class' => 'form-control')
        ));
        $builder->add('photos', CollectionType::class, array(
            'label' => "Фото",
            'entry_type' => PhotoType::class
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
            'data_class' => Section::class,
        ));
    }
}
