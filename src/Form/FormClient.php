<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Service\MessageGenerator;


// une classe Type hérite toujours de la classe abstraite AbstractType
class FormClient extends AbstractType
{
    // $builder correspond au FormBuilder. C'est lui qui sert à créer le formulaire
    // $options sont les options passées au moment de la création du Form dans le contrôleur (createForm, voir ci-après)
    // il s'agit d'un 3e argument facultatif.
    // En fonction de ces options vous pouvez créer un formulaire avec un label différent, un champ en plus ou en moins, etc
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $action_name = $options['action_name']; // $test est égal à modify

        if($action_name == 'modify'){
          $builder
          ->add('nom', TextType::class)
          ->add('prenom', TextType::class)
          ->add('mail', TextType::class)
          ->add('modify', SubmitType::class, array('label' => 'Modifier'));

        } elseif ($action_name == 'create'){
          $builder
          ->add('nom', TextType::class)
          ->add('prenom', TextType::class)
          ->add('mail', TextType::class)
          ->add('create', SubmitType::class, array('label' => 'Création'));
        }
      }

    // spécifications des options obligatoires/facultatives.
    // Cette fonction est hautement paramétrable si besoin
    public function configureOptions(OptionsResolver $resolver)
    {
        // nous spécifions ici que le paramètre que nous utilisons dans la fonction createForm (dans le contrôleur, voir ci-après) doit être une entité Téléphone
        // C'est ce qui permet à Symfony de pouvoir retrouver le type des champs du formulaire
        $resolver->setDefaults([
            'data_class' => Client::class,
            'action_name' => 'modify',
        ]);
    }
  }

?>
