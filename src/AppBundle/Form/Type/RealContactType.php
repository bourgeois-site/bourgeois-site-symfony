<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\RealContact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RealContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => "Заголовок", 'attr' => array(
                'class' => 'form-control',
                'placeholder' => "г. Дзержинск")));
        $builder->add('address', TextType::class, array(
            'label' => "Адрес", 'attr' => array(
                'class' => 'form-control',
                'placeholder' => "г. Дзержинск, ул. Грибоедова 22/11")));
        $builder->add('mainPhone', TextType::class, array(
            'label' => "Главный номер", 'attr' => array(
                'class' => 'form-control',
                'placeholder' => '+7 (8313) 23-46-46')));
        $builder->add('additionalPhone', TextType::class, array(
            'label' => "Дополнительный номер", 'required' => false, 'attr' => array(
                'class' => 'form-control',
                'placeholder' => '+7 (904) 782-65-46')));
        $builder->add('workTime', TextType::class, array(
            'label' => "Часы работы", 'attr' => array(
                'class' => 'form-control',
                'placeholder' => "Пн-Пт: 9:00-19:00, Сб-Вс: 10:00-16:00")));
        $builder->add('latitude', NumberType::class, array(
            'label' => "Широта(latitude) - для показа точки на карте", 'attr' => array(
                'class' => 'form-control',
                'placeholder' => '56.237328')));
        $builder->add('longitude', NumberType::class, array(
            'label' => "Долгота(longitude) - для показа точки на карте", 'attr' => array(
                'class' => 'form-control',
                'placeholder' => '43.448866')));
        $builder->add('submit', SubmitType::class, array(
            'label' => "Подтвердить", 'attr' => array(
                'class' => 'btn btn-primary btn-lg')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => RealContact::class,
        ));
    }
}
