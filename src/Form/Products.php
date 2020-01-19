<?php


namespace App\Form;

use App\Entity\Product;


use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class Products extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class)
            ->add('modelNumber',TextType::class)
            ->add('color',TextType::class)
            ->add('price',TextType::class)
            ->add('category',TextType::class)
            ->add('boughtCounter',TextType::class)
            ->add('description',TextType::class)
            ->add('Sizes',TextType::class)
            ->add('isPromotion',CheckboxType::class)
            ->add('discountPrice',TextType::class)
            ->add('description',TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
