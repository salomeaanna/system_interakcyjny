<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 */
#[Route('/user')]
class UserController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService User service interface
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(private readonly UserServiceInterface $userService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Action edit user.
     *
     * @param Request                     $request        Http request
     * @param UserPasswordHasherInterface $passwordHasher Password Hasher
     *
     * @return Response HTTP response
     */
    #[Route(
        '/user_edit',
        name: 'user_edit',
        methods: 'GET|POST'
    )]
    public function edit(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, null, [
            'current_email' => $user->getEmail(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($passwordHasher->isPasswordValid($user, $data['current_password'])) {
                $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
            } else {
                $this->addFlash(
                    'danger',
                    $this->translator->trans('message.invalid_current_password')
                );

                return $this->redirectToRoute('user_edit');
            }

            $user->setEmail($data['email']);
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.user_edited')
            );

            return $this->redirectToRoute('user_edit');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
