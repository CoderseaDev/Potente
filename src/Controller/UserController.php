<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use App\Form\UserEdit;
use App\Form\UserProfileData;
// use http\Env\Request;
use Intervention\Image\Facades\Image as Img;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameWorkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Flex\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Image;
use App\Form\ImageUploadType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


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
    }

    /**
     * @Route("/user/edit/{id}",name="edit_user")
     * Method({"GET"})
     */
    public function edit(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(UserEdit::class, $user);
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
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Symfony\Component\Asset\Packages $assetsManager)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $this->addFlash('notice', 'Post Submitted Successfully!!!');

            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                return $this->redirectToRoute('user_list');
            }
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


