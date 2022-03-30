<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pathToLogo', FileType::class, [
                'label'    => 'Logo (Optional)',
                'block_name' => 'custom_logo',
                'required'   => false,
            ])
            ->add('pathToDbFile', FileType::class, [
                'label'    => 'Base de datos (.db):'
            ])
            ->add('name', TextType::class, [
                'label'    => 'Nombre del proyecto'
            ])
            ->add('description', TextareaType::class,[
                'label'    => 'Descripcion del proyecto'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
