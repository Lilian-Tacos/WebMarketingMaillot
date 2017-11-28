<?php

namespace NewSoccerJersey\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class JerseyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', 'text')
            ->add('Description', 'textarea')
            ->add('Type', 'text')
            ->add('Team', 'text')
            ->add('Price', 'text');            
    }

    public function getName()
    {
        return 'jersey';
    }
}