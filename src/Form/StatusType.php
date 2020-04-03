<?php

namespace App\Form;

use App\Entity\Time;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class StatusType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('logon', SubmitType::class, ['label' => 'Prisijungęs'])
			->add('away', SubmitType::class, ['label' => 'Pertrauka'])
			->add('stats', SubmitType::class, ['label' => 'Statistika'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Time::class,

        ]);
    }
}
