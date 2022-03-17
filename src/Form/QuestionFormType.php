<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Form\Type\TemplateQuestionType;

class QuestionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('pathToDbFile', FileType::class, [
            'attr' => array(
                'class' => 'block
                            px-3
                            py-1
                            text-base
                            font-normal
                            bg-transparent bg-clip-padding
                            border border-solid border-gray-300
                            rounded
                            m-0
                            text-white
                            focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none'
            ),
            'label'    => 'Introduce el fichero de base de datos sobre el que quieres realizar queries:', 
            'label_attr' => array ('class'=>'font-sans text-white'),
            'row_attr' => array('class' => 'py-10 grid justify-items-center'),
        ])
        ->add('templateQuestions', CollectionType::class, [
            'entry_type' =>  TemplateQuestionType::class,
            'allow_add' => true,
            'entry_options' => array('label' => false),
            'label' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
