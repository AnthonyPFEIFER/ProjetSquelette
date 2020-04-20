<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ContactType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     */
    public function users()
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users
        ]);
    }
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request)
    {
        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contactForm->getData();
        }

        return $this->render('user/contact.html.twig', [
            'contactForm' => $contactForm->createView()
        ]);
    }
    /**
     * @Route("/new-user", name="user_new")
     */
    public function newUser(Request $request)
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setCreatedAt(new \DateTime());
            ($user === $userForm->getData());
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('user/newUser.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }
}
