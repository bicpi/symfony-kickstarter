<?php

namespace Acme\DemoBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\FormType
 */
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'label.name'
            ])
            ->add('email', null, [
                'label' => 'label.email'
            ])
            ->add('country', null, [
                'label' => 'label.country',
                'empty_value' => 'label.empty',
                'property' => sprintf('translations[%s].title', $options['locale']),
                'query_builder' => function(EntityRepository $er) use($options) {
                    return $er->createQueryBuilder('e')
                        ->select('e, et')
                        ->leftJoin('e.translations', 'et')
                        ->where("et.locale = :locale")
                        ->orderBy('et.title', 'ASC')
                        ->setParameter('locale', $options['locale']);
                },
            ])
            ->add('items', null, [
                'label' => 'label.items',
                'group_by' => sprintf('category.translations[%s].title', $options['locale']),
                'property' => sprintf('translations[%s].title', $options['locale']),
                'query_builder' => function(EntityRepository $er) use($options) {
                        return $er->createQueryBuilder('e')
                            ->select('e, et, ec, ect')
                            ->leftJoin('e.translations', 'et')
                            ->leftJoin('e.category', 'ec')
                            ->leftJoin('ec.translations', 'ect')
                            ->where("et.locale = :locale")
                            ->orderBy('ect.title', 'ASC')
                            ->addOrderBy('et.title', 'ASC')
                            ->setParameter('locale', $options['locale']);
                    },
                'attr' => [
                    'style' => 'height:150px'
                ]
            ])
            ->add('submit', 'submit', [
                'label' => 'button.submit',
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
                'data_class' => 'Acme\DemoBundle\Entity\Registration',
                'locale' => 'en'
            ]
        );
    }

    public function getName()
    {
        return 'registration_form';
    }
}
