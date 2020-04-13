<?php   
namespace App\FormCrear;

use App\Entity\Producto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre y apellido',
                'attr' => ['class' => 'col-md-12 form-control'],
            ])
            ->add('codigo', TextType::class, [
                'label' => 'Codigo',
                'attr' => ['class' => 'col-md-12 form-control'],
            ])
            ->add('descripcion', TextareaType::class, [ 
                'attr' => ['class' => 'col-md-12 form-control']
            ]) 
            ->add('precio_costo', TextType::class,[ 
                'attr' => ['class' => 'col-md-12 form-control']
            ])
            ->add('precio_venta', TextType::class, [ 
                'attr' => ['class' => 'col-md-12 form-control']
            ])
            ->add('stock', TextType::class, [ 
                'attr' => ['class' => 'col-md-12 form-control']
            ]) 
            ->add('submit', SubmitType::class, [
                'label' => 'Guardar',
                'attr' => ['class' => 'btn btn-info text-center form-control'],
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