<?php

namespace Form;

use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('password', PasswordType::class)
            ->add('submit', SubmitType::class);
    }
}
