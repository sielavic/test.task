<?php

namespace App\Form;

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

class TransferDeveloperType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('position', ChoiceType::class, array(
                'label' => 'Должность',
                'choices' => array(
                    'программист' => 'программист',
                    'администратор' => 'администратор',
                    'devops' => 'devops',
                    'дизайнер' => 'дизайнер'
                )))
            ->add('projects', ChoiceType::class, array(
                'label' => 'Проект',
                'choices' => $options['projects'],
                'mapped' => false,
                'choice_label' => function ($project) {
                    /** @var Project $project */
                    return $project->getTitle();
                }
            ))



            ->add('submit', SubmitType::class, array(
                'label' => 'Сохранить'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'projects' => Project::class
        ]);

    }


}