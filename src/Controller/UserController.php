<?php

namespace App\Controller;

use App\Entity\User;
// use http\Env\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameWorkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Flex\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends Controller
{
    /**
     * @Route("/",name="user_list")
     * Method({"GET","POST"})
     */
    public function index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('user/index.html.twig', array('users' => $users));

//         $articles=['article 1','article 2'];
//         return new SymfonyResponse('<html><body>Hello</body></html>');
//         return $this->render('articles/index.html.twig',array('name'=>'brand'));
//         return $this->render('articles/index.html.twig',array('articles' => $articles));
    }


    /**
     * @Route("/user/edit/{id}",name="edit_user")
     * Method({"GET"})
     */
    public function edit(Request $request, $id)
    {
        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('email', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('save', submitType::class, array(
                'label' => 'update',
                'attr' => array('class' => 'btn btn-dark mt-3')))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $form->getData();
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
    public function new(Request $request)
    {
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('email', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('password', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('save', submitType::class, array(
                'label' => 'create',
                'attr' => array('class' => 'btn btn-dark mt-3')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
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

    /**
     * @Route("/user/save")
     */
    public function save()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new  User();
//         $user->settitle("article one");
//         $user->setbody("body one");
        $entityManager->persist($user);
        $entityManager->flush();
        return new SymfonyResponse('saved user with id' . $user->getId());
    }

}


