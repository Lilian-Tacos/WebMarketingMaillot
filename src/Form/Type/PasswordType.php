<?php

namespace NewSoccerJersey\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', 'password', array(
                'label'	          => 'Mot de passe actuel')
            )
            ->add('newPassword', 'repeated', array(
                'type'            => 'password',
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'options'         => array('required' => true),
                'first_options'   => array('label' => 'Nouveau mot de passe'),
                'second_options'  => array('label' => 'Répéter le nouveau mot de passe'),
            ));
    }

    public function getName()
    {
        return 'user';
    }
}