<?php
/**
 * @author Pavel Vasiluk <pavel.vasiluk@gmail.com>
 * Date: 3/14/2020
 * Time: 3:16 AM
 */

namespace App\Controller;

use App\Entity\User;
use App\Service\UserManager;
use App\Validator\ValidationManager;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Security controller
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     *
     * @param AuthenticationUtils $authenticationUtils Symfony Authentication Utils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return new RedirectResponse($this->generateUrl('rss-reader'));
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            ['last_username' => $lastUsername, 'error' => $error]
        );
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->redirect($this->generateUrl('login'));
    }

    /**
     * @Route("/sign-up", name="sign_up", methods={"GET","POST"})
     *
     * @param Request           $request           Request
     * @param ValidationManager $validationManager Validation manager
     * @param UserManager       $userManager       User manager
     *
     * @return Response
     * @throws ORMException
     */
    public function signUp(
        Request $request,
        ValidationManager $validationManager,
        UserManager $userManager
    ): Response
    {
        if ($request->isMethod('POST')) {
            $username = $request->request->get('email');
            $password = $request->request->get('password');
            $data = compact(['username', 'password']);

            /** @var User $entity */
            $entity = $validationManager->fillEntityWithData(new User(), $data);
            $errors = $validationManager->validateEntity($entity);

            if (!empty($errors)) {
                return new JsonResponse(['errors' => $errors]);
            }

            $userManager->addUser($entity);

            $responseKey = 'message';
            $responseMessage = sprintf(
                'User with email %s has been successfully verified.',
                $entity->getUsername()
            );
            $response = [$responseKey => $responseMessage];

            return new JsonResponse(['success' => $response]);
        }

        return $this->render('security/sign-up.html.twig');
    }

    /**
     * @Route("/validate_email", name="validate_email", methods={"POST"})
     *
     * @param Request           $request           Request
     * @param ValidationManager $validationManager Validation manager
     *
     * @return JsonResponse
     */
    public function validateEmail(Request $request, ValidationManager $validationManager)
    {
        $username = $request->request->get('email');
        $data     = compact(['username']);

        $entity = $validationManager->fillEntityWithData(new User(), $data);
        $validationResult = $validationManager->validateEntity($entity);

        return new JsonResponse(['errors' => $validationResult]);
    }
}