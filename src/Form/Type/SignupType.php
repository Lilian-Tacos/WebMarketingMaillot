<?php


namespace NewSoccerJersey\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array( 
                'label'           => 'Prénom'))
            ->add('lastName', 'text', array( 
                'label'           => 'Nom'))
            ->add('mail', 'email', array(
                'label'           => 'email'))
            ->add('address', 'text', array( 
                'label'           => 'Adresse'))
            ->add('postalCode', 'text', array( 
                'label'           => 'Code postal'))
            ->add('city', 'text', array( 
                'label'           => 'Ville'))
            ->add('password', 'repeated', array(
                'type'            => 'password',
                'invalid_message' => 'Les mots de passe doivent correspondre',
                'required'        => true,
                'first_options'   => array('label' => 'Mot de passe'),
                'second_options'  => array('label' => 'Répétez le mot de passe'),
            ));
    }

    public function getName()
    {
        return 'signup';
    }
}