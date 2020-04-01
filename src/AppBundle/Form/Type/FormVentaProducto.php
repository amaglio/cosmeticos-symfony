<?php   
namespace App\PostTypeProductoVenta;

 
use App\Entity\ProductoVenta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
 use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
 
class PostTypeProductoVenta extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('venta_id', TextType::class)
            ->add('producto_id', TextType::class) 
            ->add('cantidad', TextType::class) 
            ->add('submit', SubmitType::class, [
                'label' => 'Agregar',
                'attr' => ['class' => 'btn btn-primary'],
            ]); 
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductoVenta::class,
        ]);
    }
}












?>