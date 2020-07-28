<?php   
namespace App\FormCrear;

use App\Entity\Producto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class FormProducto extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre del producto',
                'attr' => ['class' => 'col-md-12 form-control']
            ])
            ->add('codigo', IntegerType::class, [
                'label' => 'Codigo',
                'attr' => ['class' => 'col-md-12 form-control']
            ])
            ->add('descripcion', TextareaType::class, [ 
                'attr' => ['class' => 'col-md-12 form-control']
            ]) 
            ->add('precio_costo', MoneyType::class, [ 
                'attr' => [ 'class' => 'col-md-12 form-cont,rol',
                            'step' => 0.01 ]
            ])
            ->add('precio_venta', MoneyType::class, [ 
                'attr' => ['class' => 'col-md-12 form-control',
                'step' => 0.01]
            ])
            ->add('stock', IntegerType::class, [ 
                'attr' => ['class' => 'col-md-12 form-control']
            ]) 
            ->add('imagen', FileType::class, [
                'label' => 'Imagen del producto',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Ingrese un archivo con formato de imagen',
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Guardar',
                'attr' => ['class' => 'btn btn-info btn-xs text-center form-control'],
            ]); 
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Producto::class,
        ]);
    }
}












?>