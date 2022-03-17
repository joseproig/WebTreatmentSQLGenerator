<?php

namespace App\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\TemplateQuestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TemplateQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('templateQuestion', TextareaType::class, [
            'attr' => array (
                'class' => 'block
                            w-full
                            my-3
                            text-base
                            font-normal
                            text-gray-700
                            bg-white bg-clip-padding
                            border border-solid border-gray-300
                            rounded
                            transition
                            ease-in-out
                            m-0
                            focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                'placeholder' => 'Tu modelo de plantilla...',
                'rows' => '7'
            ),
            'label'    => 'Plantilla de la query que quieres usar:', 
            'label_attr' => array ('class'=>'font-sans text-slate-900'),
            'row_attr' => array('class' => 'w-full grid justify-items-center bg-slate-100 px-3 rounded-lg py-3'),
            
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TemplateQuestion::class,
        ]);
    }
}
