<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\InternetContact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InternetContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => "Заголовок (будет выскакивать при наведении пользователем на контакт)", 'attr' => array(
                'class' => 'form-control',
                'placeholder' => "ВКонтакте")));
        $builder->add('href', TextType::class, array(
            'label' => "Ссылка (url или email)", 'attr' => array(
                'class' => 'form-control',
                'placeholder' => "https://vk.com/remont_dzer")));
        $builder->add('isEmail', ChoiceType::class, array(
            'label' => "Email? (Нет, если контакт - социальная сеть)", 'choices' => array(
                "Нет" => false,
                "Да" => true
            ), 'attr' => array('class' => 'form-control')));
        $builder->add('socialName', ChoiceType::class, array(
            'label' => "Соц. сеть - выбор нужного значка (Нет, если контакт - email)", 'choices' => array(
                "Нет" => null,
                "ВКонтакте" => 'vk',
                "Одноклассники" => 'odnoklassniki',
                "Facebook" => 'facebook',
                "Instagram" => 'instagram',
                "YouTube" => 'youtube',
                "Telegram" => 'telegram',
                "Twitter" => 'twitter',
                "Skype" => 'skype'
            ), 'attr' => array('class' => 'form-control')));
        $builder->add('submit', SubmitType::class, array(
            'label' => "Подтвердить", 'attr' => array(
                'class' => 'btn btn-primary btn-lg')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => InternetContact::class,
        ));
    }
}
