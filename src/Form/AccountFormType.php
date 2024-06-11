<?php
namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Entity\Subscription;

class AccountFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('firstname', null, [
                'label' => 'Prénom',
            ])
            ->add('lastname', null, [
                'label' => 'Nom de famille',
            ])
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
                'constraints' => [
                    new UserPassword(['message' => 'Mot de passe actuel incorrect']),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'mapped' => false,
                'required' => false,
                'help' => 'Laissez vide si vous ne souhaitez pas changer de mot de passe',
            ])
            ->add('subscription', EntityType::class, [
                'class' => Subscription::class,
                'choice_label' => function(Subscription $subscription) {
                    return sprintf('%s (%d PDFs)', ucfirst($subscription->getTitle()), $subscription->getPdfLimit());
                },
                'label' => 'Plan d\'abonnement',
                'placeholder' => 'Choisissez un plan',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un plan d\'abonnement',
                    ]),
                ],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $user = $form->getData();
    
                $subscription = $user->getSubscriptionId();
                if ($subscription) {
                    $pdfLimit = $subscription->getPdfLimit();
                    $user->setUserCredits($pdfLimit);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\User',
        ]);
    }
}
