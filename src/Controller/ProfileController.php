<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Image;
use App\Form\ImageUploadType;
use App\Form\UserProfileData;


class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        $user = $this->getUser();
        $username = $user->getUsername();
        $useremail = $user->getEmail();
        $userlastname = $user->getlastname();
        $usermobile = $user->getmobile();
        $userphone = $user->getphone();
        $userlocation = $user->getlocation();


        $user = new User();
        $userId = $this->getUser()->getId();

        $query = $this->getDoctrine()->getManager()->getConnection()
            ->prepare("SELECT url FROM image LEFT JOIN user ON image.user_id_id = user.id WHERE user_id_id = $userId");
        $query->execute();
        $results = $query->fetchAll();

        $imageEn = new Image();

        $url = !empty($results) ? $results[0]['url'] : '/uploads/profile_images/default.jpg';

        return $this->render('profile/profile.html.twig',
            array(
                'username' => $username,
                'useremail' => $useremail,
                'userlastname' => $userlastname,
                'usermobile' => $usermobile,
                'userphone' => $userphone,
                'userlocation' => $userlocation,
                'url' => $url
            ));
    }

    /**
     * @Route("/saveProfileImage", name="save_profile_image")
     * Method({"POST"})
     */
    public function saveProfileImages(Request $request, \Symfony\Component\Asset\Packages $assetsManager)
    {
        $em = $this->getDoctrine()->getManager();
        $imageEn = new Image();

        $user = $this->getUser();

        $file = $request->files->get('image_upload');

        $user = new User();
        $userId = $this->getUser()->getId();

        $query = $this->getDoctrine()->getManager()->getConnection()
            ->prepare("SELECT id FROM image WHERE user_id_id = $userId");
        $query->execute();
        $results = $query->fetchAll();
        if (!$results) {

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
            $file->move($this->getParameter('uploads_directory'), $fileName);
            $imageEn->setName($fileName);
            $imageEn->setImage($fileName);
            $imageEn->setSize($size);
            $imageEn->setExt($extension);
            $imageEn->setMinType($mimetype);
            $imageEn->setHashName($hashname);
            $url = '/' . 'uploads' . '/' . 'profile_images' . '/' . $fileName;
            $imageEn->setUrl($url);
            $imageEn->setUserId($user);
            $em->persist($imageEn);

            $response = new Response(json_encode(array("url" => $url)));
            $response->headers->set('Content-Type', 'application/json');
            $em->flush();
            return $response;
        } else {
            $imageId = $results[0]["id"];
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
            $file->move($this->getParameter('uploads_directory'), $fileName);
            $url = '/' . 'uploads' . '/' . 'profile_images' . '/' . $fileName;

            $sql = "UPDATE image SET 
                              name='$fileName',
                              hash_name='$hashname',
                              size='$size',
                              ext='$extension',
                              min_type='$mimetype',
                              url='$url',
                              image='$fileName'
                               where id = '$imageId'";
            $this->getDoctrine()->getManager()->getConnection()
                ->prepare($sql)->execute();
            $response = new Response(json_encode(array("url" => $url)));
            $response->headers->set('Content-Type', 'application/json');
            $em->flush();
            return $response;
        }
    }

    /**
     * @Route("/saveProfileFields",name="save_profile_fields")
     * Method({"POST"})
     */
    public function saveProfileFields(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $profile = $entityManager->getRepository(User::class)->find($this->getUser()->getId());
        $userData = $request->request->all();
        $profile->setlastname($userData['lastname']);
        $profile->setphone($userData['phone']);
        $profile->setmobile($userData['mobile']);
        $profile->setlocation($userData['location']);
        $entityManager->flush();
        return $this->redirectToRoute('profile');
    }

}
