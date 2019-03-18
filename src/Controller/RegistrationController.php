<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Services\Fetcher;
use App\Services\Paginator;
use App\Services\Sum;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder , TranslatorInterface $translator, Fetcher $fetcher, Paginator $paginator, Sum $sum)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array(
                'label' => $translator->trans('User Name'),
                'attr' => array('class' => 'form-control')
              ))

            ->add('email', EmailType::class, array(
                'label' => $translator->trans('user email'),
                'attr' => array('class' => 'form-control')))

            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array(
                    'label' => $translator->trans('Password'),
                    'attr' => array('class' => 'form-control')),
                'second_options' => array(
                    'label' => $translator->trans('Repeat Password'),
                    'attr' => array('class' => 'form-control'))))

            ->add('save', submitType::class, array(
                'label' => $translator->trans('Save'),
                'attr' => array('class' => 'btn btn-dark mt-3')))
            ->getForm();


        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('user_list');
        }

        $result= $fetcher->get('https://api.coinmarketcap.com/v2/listings/');
        $partialArray= $paginator->getPartial($result['data'],10,10);
        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView(),
                  //'getURL' => $fetcher->get('https://api.coinmarketcap.com/v2/listings/')
                  'partial_array' =>$partialArray,
                  'getsum' => $sum->getPartial(10 ,60)
            )
        );
    }
}
