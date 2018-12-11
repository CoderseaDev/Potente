<?php

namespace App\Controller;

//use App\Form\UserType;
use App\Entity\User;
//use App\Form\UserEdit;
use App\Form\UserProfileData;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameWorkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;




class UserController extends Controller
{
    /**
     * @Route("/",name="user_list")
     * Method({"GET","POST"})
     */
    public function index()
    {
        $currentUserId = $this->getUser()->getId();
        $query = $this->getDoctrine()->getManager()->getConnection()
            ->prepare("SELECT * FROM user  WHERE id != $currentUserId");
        $query->execute();
        $users = $query->fetchAll();

        return $this->render('user/index.html.twig', array('users' => $users));
    }

    /**
     * @Route("/user/edit/{id}",name="edit_user")
     * Method({"GET"})
     */
    public function edit(Request $request, $id , TranslatorInterface $translator)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
//        $form = $this->createForm(UserEdit::class, $user);


        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array(
                'label' => $translator->trans('User Name'),
                'attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class, array(
                'label' => $translator->trans('user email'),
                'attr' => array('class' => 'form-control')))
            ->add('save', submitType::class, array(
                'label' => $translator->trans('update'),
                'attr' => array('class' => 'btn btn-dark mt-3')))

            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('user_list');
        }
        return $this->render("user/edit.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/user/new",name="new_user")
     * Method({"GET"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder , TranslatorInterface $translator)
    {

        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array(
                'label' => $translator->trans('User Name'),
                'attr' => array('class' => 'form-control',
                )))

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

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $this->addFlash('notice', 'Post Submitted Successfully!!!');

            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


               return $this->redirectToRoute('user_list');

        }
        return $this->render("user/new.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/user/{id}",name="user_show")
     */
    public function show($id)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->find($id);
        return $this->render('user/show.html.twig', array('users' => $users));

    }

    /**
     * @Route("/user/delete/{id}",name="delete_user")
     */
    public function delete(Request $request, $id)
    {

        $users = $this->getDoctrine()->getRepository(User::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($users);
        $entityManager->flush();
        $response = new SymfonyResponse();
        $response->send();

    }

}


