<?php   
namespace App\PostTypeVenta;

use App\Entity\Venta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PostTypeVenta extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('nombre', TextType::class,[
                'label' => 'Nombre y apellido',
                'attr' => ['class' => 'col-md-12 form-control']
            ])
            ->add('fecha', DateType::class,[
                'label' => 'Fecha',
                'attr' => ['class' => 'col-md-12  ']
            ])
            ->add('nombre', TextType::class,[
                'label' => 'Nombre y apellido',
                'attr' => ['class' => 'col-md-12 form-control']
            ])
            ->add('telefono', TextType::class,[
                'label' => 'Telefono',
                'attr' => ['class' => 'col-md-12 form-control']
            ])
            ->add('email', TextType::class,[
                'label' => 'Email',
                'attr' => ['class' => 'col-md-12 form-control']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Guardar',
                'attr' => ['class' => 'btn btn-info text-center form-control btn-info text-center form-control'],
            ]); 
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Venta::class,
        ]);
    }
}












?>