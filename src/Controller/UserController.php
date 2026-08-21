<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller used to manage current user. The #[CurrentUser] attribute
 * tells Symfony to inject the currently logged user into the given argument.
 * It can only be used in controllers and it's an alternative to the
 * $this->getUser() method, which still works inside controllers.
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
#[Route('/profile'), IsGranted(User::ROLE_USER)]
final class UserController extends AbstractController
{
    #[Route('/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
		error_log(sprintf('>>> Entering edit($user=%s,$request=%s,$entityManager=%s)', is_scalar($user) ? $user : gettype($user), is_scalar($request) ? $request : gettype($request), is_scalar($entityManager) ? $entityManager : gettype($entityManager)));
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$__logStart = microtime(true);
            $entityManager->flush();

            $this->addFlash('success', 'user.updated_successfully');

            return $this->redirectToRoute('user_edit', [], Response::HTTP_SEE_OTHER);
        }

		error_log(sprintf('<<< Exiting edit($user=%s,$request=%s,$entityManager=%s)', is_scalar($user) ? $user : gettype($user), is_scalar($request) ? $request : gettype($request), is_scalar($entityManager) ? $entityManager : gettype($entityManager)));
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/change-password', name: 'user_change_password', methods: ['GET', 'POST'])]
    public function changePassword(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security,
    ): Response {
		error_log(sprintf('>>> Entering changePassword($user=%s,$request=%s,$entityManager=%s,$security=%s)', is_scalar($user) ? $user : gettype($user), is_scalar($request) ? $request : gettype($request), is_scalar($entityManager) ? $entityManager : gettype($entityManager), is_scalar($security) ? $security : gettype($security)));
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
			error_log(sprintf('changePassword($user,$request,$entityManager,$security)=%s,%s,%s,%s: database query executed in %s ms', is_scalar($user) ? $user : gettype($user), is_scalar($request) ? $request : gettype($request), is_scalar($entityManager) ? $entityManager : gettype($entityManager), is_scalar($security) ? $security : gettype($security), round((microtime(true) - $__logStart) * 1000)));

            // The logout method applies an automatic protection against CSRF attacks;
            // it's explicitly disabled here because the form already has a CSRF token validated.
            return $security->logout(validateCsrfToken: false) ?? $this->redirectToRoute('homepage');
        }

		error_log(sprintf('<<< Exiting changePassword($user=%s,$request=%s,$entityManager=%s,$security=%s)', is_scalar($user) ? $user : gettype($user), is_scalar($request) ? $request : gettype($request), is_scalar($entityManager) ? $entityManager : gettype($entityManager), is_scalar($security) ? $security : gettype($security)));
        return $this->render('user/change_password.html.twig', [
            'form' => $form,
        ]);
    }
}
