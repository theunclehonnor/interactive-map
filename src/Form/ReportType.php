<?php

namespace App\Form;

use App\Entity\Report;
use App\Entity\Source;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;

class ReportType extends AbstractType
{
    private $entityManager;
    private $reports;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $reportsBD = $this->entityManager->getRepository(Report::class)->findAll();
        $reports = [];
        for($i = 0; $i < count($reportsBD); $i++) {
            $reports[$i] = $reportsBD[$i]->getName();
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('source', EntityType::class, array(
                'label' => 'Источник данных:',
                'class' => Source::class,
                'choice_label' => 'name'
            ))
            ->add('name', ChoiceType::class, array(
                'label' => 'Название отчета:',
                'choices' => $this->reports,
                'choice_label' => function ($choice) {
                    return $choice;
                }
            ))
            ->add('indicator', ChoiceType::class, array(
                'label' => 'Показатель:',
                'choices' => $this->entityManager->getRepository(Report::class)->findAll(),
                'choice_value' => 'indicator',
                'choice_label' => 'indicator',
            ))
            ->add('type_report', ChoiceType::class, array(
                'label' => 'Тип отчета:',
                'choices' => $this->entityManager->getRepository(Report::class)->findAll(),
                'choice_value' => 'type_report',
                'choice_label' => 'type_report',
            ))
            ->add('type', ChoiceType::class, array(
                'label' => 'Тип:',
                'choices' => $this->entityManager->getRepository(Report::class)->findAll(),
                'choice_value' => 'type',
                'choice_label' => 'type',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
        ]);
    }
}
