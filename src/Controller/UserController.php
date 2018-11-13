<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use App\Form\UserEdit;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $form = $this->createForm(UserEdit::class,$user);
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
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

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


