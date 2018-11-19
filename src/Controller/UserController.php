<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use App\Entity\Files;
use App\Form\UserEdit;
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
        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(UserEdit::class, $user);
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
        $entityManager->persist($user);
        $entityManager->flush();
        return new SymfonyResponse('saved user with id' . $user->getId());
    }


    /**
     * @Route("/image", name="image_upload")
     * Method({"GET"})
     */
    public function indexAction(Request $request, \Symfony\Component\Asset\Packages $assetsManager)
    {
        $em = $this->getDoctrine()->getManager();
        $imageEn = new Image();

        $user = $this->getUser();

        $form = $this->createForm(ImageUploadType::class, $imageEn);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $imageEn->getImage();

            //mimetype
            $mimetype = $file->getClientMimeType();
            //file_Extension
            $extension = $file->guessExtension();
            //hashName
            $hashname = md5(uniqid());
            //file_Name
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            //image_path
            $path = $assetsManager->getUrl('uploads/profile_images/' . $fileName);
            //file_Name
            $filename = $file->getClientOriginalName();
            //file_size
            $size = $file->getClientSize();
            //file_URL
            $url = "fsdfsdf";
            $file->move($this->getParameter('uploads_directory'), $fileName);
            $imageEn->setName($fileName);
            $imageEn->setImage($fileName);
            $imageEn->setSize($size);
            $imageEn->setExt($extension);
            $imageEn->setMinType($mimetype);
            $imageEn->setHashName($hashname);
            $imageEn->setUrl('/' . 'uploads' . '/' . 'profile_images' . '/' . $fileName);
            $imageEn->setUserId($user);
            $em->persist($imageEn);
            $em->flush();

            $this->addFlash('notice', 'Post Submitted Successfully!!!');

            return $this->redirectToRoute('user_list');
        }
        return $this->render('image/image.html.twig', array(

            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/image", name="image_upload")
     */
//    public function upload(Request $request)
//    {
//        $folder="";
//        $em = $this->getDoctrine()->getManager();
//        $imageEn = new f();
//        $form = $this->createForm(ImageUploadType::class, $imageEn);
//        $form->handleRequest($request);
//        if($form->isSubmitted() && $form->isValid()) {
//            $file = $imageEn->getImage();
//            if ($file) {
//                $extension = $file->guessClientExtension();
//                $tempPath = $file->getPathName();
//                $ImageFileData = Img::make($tempPath);
//                $size = $ImageFileData->filesize();
////                // image info (width and height)
////                $image_width = $ImageFileData->width();
////                $image_height = $ImageFileData->height();
//                $filename = $file->getClientOriginalName();
//                $type = $file->getMimeType();
//                $hash = str_random(20);
//                $hashName = $hash . "." . $extension;
//                $ImgPath = public_path($folder . '/' . $hashName);
//                $ImageFileData->save($ImgPath, 75)->encode('jpg', 60);
//                $ImageFileData->destroy();
//
//
//                return $type;
//            }
//        }
//        return $this->render('image/image.html.twig', array(
//            'form' => $form->createView()
//        ));
//
//    }


}


