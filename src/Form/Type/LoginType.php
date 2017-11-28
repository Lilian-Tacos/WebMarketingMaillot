<?php


namespace NewSoccerJersey\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail', 'email', array(
                'label'     => 'email'))
            ->add('password', 'password', array(
                'label'     => 'Mot de passe'
            ));
    }

    public function getName()
    {
        return 'login';
    }
}