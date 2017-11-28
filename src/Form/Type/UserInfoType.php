<?php


namespace NewSoccerJersey\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array( 
                'label'           => 'Prénom'))
            ->add('lastName', 'text', array( 
                'label'           => 'Nom'))
            ->add('address', 'text', array( 
                'label'           => 'Adresse'))
            ->add('postalCode', 'text', array( 
                'label'           => 'Code postal'))
            ->add('city', 'text', array( 
                'label'           => 'Ville'));
    }

    public function getName()
    {
        return 'user_info';
    }
}