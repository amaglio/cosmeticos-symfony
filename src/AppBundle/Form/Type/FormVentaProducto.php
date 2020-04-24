<?php   
namespace App\PostTypeProductoVenta;

use App\Entity\ProductoVenta;
use App\Entity\Venta;
use App\Entity\Producto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
 use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType; 
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class PostTypeProductoVenta extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('venta_id', EntityType::class, [
                    'class' => Venta::class,
                    'label' => false,
                    'attr' => ['disabled' => true,  'class' => 'form-control'   ] 
                ])
            ->add('producto_id',EntityType::class, [
                'class' => Producto::class,
                'label' => false,
                'attr' => [   'class' => 'form-control' ] 
            ])
            ->add('precio_costo', HiddenType::class, [
                'attr' => ['readonly' => false ],
                'required' => false
                ])
            ->add('precio_venta', HiddenType::class, [
                'attr' => ['readonly' => false],
                'required' => false
                ])
            ->add('cantidad', NumberType::class, [
                'html5' => true,
                'label' => false,
                'attr' => ['placeholder' => "Ingrese la cantidad" ,
                                'required' => false,
                                'class' => 'form-control',
                        ]
                ])
            ->add('submit', SubmitType::class, [
                'label' => 'Agregar',
                'attr' => ['class' => 'btn btn-info mb-2'],
                ]); 
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductoVenta::class,
        ]);
 
    }
}
