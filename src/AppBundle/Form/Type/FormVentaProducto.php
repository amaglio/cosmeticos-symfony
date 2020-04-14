<?php   
namespace App\PostTypeProductoVenta;

use App\Entity\ProductoVenta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
 use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType; 
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class PostTypeProductoVenta extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('venta_id', HiddenType::class, array('attr' => array('readonly' => true)) )
            ->add('producto_id', HiddenType::class, array('attr' => array('readonly' => true)) ) 
            ->add('precio_costo', HiddenType::class, array('attr' => array('readonly' => true)) )
            ->add('precio_venta', HiddenType::class, array('attr' => array('readonly' => true)))
            ->add('cantidad', NumberType::class, [
                'html5' => true,
                'attr' => array('placeholder' => "Ingrese la cantidad2" ,
                                'required' => true,
                                 )
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Agregar',
                'attr' => ['class' => 'btn btn-info'],
            ]); 
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductoVenta::class,
        ]);

        $resolver->setDefaults(array(
            'producto_id' => '1',
        ));
    }
}












?>