<?php

namespace App\Form;

use App\Entity\Developer;
use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class CreateProjectType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('title', TextType::class, array(
            'label' => 'Название'
        ))
            ->add('customer', TextType::class, array(
                'label' => 'Заказчик'
            ))
            ->add('developers', ChoiceType::class, array(
                'label' => 'Разработчик',
                'choices' => $options['developers'],
                'mapped' => false,
                'choice_label' => function ($developer) {
                    /** @var Developer $developer */
                    return $developer->getFullName();
                }
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Сохранить'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'developers' => Developer::class
        ]);

    }


}