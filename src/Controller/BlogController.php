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

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Event\CommentCreatedEvent;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller used to manage blog contents in the public part of the site.
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
#[Route('/blog')]
final class BlogController extends AbstractController
{
    /**
     * NOTE: For standard formats, Symfony will also automatically choose the best
     * Content-Type header for the response.
     *
     * See https://symfony.com/doc/current/routing.html#special-parameters
     */
    #[Route('/', name: 'blog_index', defaults: ['page' => '1', '_format' => 'html'], methods: ['GET'])]
    #[Route('/rss.xml', name: 'blog_rss', defaults: ['page' => '1', '_format' => 'xml'], methods: ['GET'])]
    #[Route('/page/{page}', name: 'blog_index_paginated', defaults: ['_format' => 'html'], requirements: ['page' => Requirement::POSITIVE_INT], methods: ['GET'])]
    #[Cache(smaxage: 10)]
    public function index(Request $request, int $page, string $_format, PostRepository $posts, TagRepository $tags): Response
    {
		error_log(sprintf('>>> Entering index($request=%s,$page=%s,$_format=%s,$posts=%s,$tags=%s)', is_scalar($request) ? $request : gettype($request), is_scalar($page) ? $page : gettype($page), is_scalar($_format) ? $_format : gettype($_format), is_scalar($posts) ? $posts : gettype($posts), is_scalar($tags) ? $tags : gettype($tags)));
        $tag = null;

        if ($request->query->has('tag')) {
			$__logStart = microtime(true);
            $tag = $tags->findOneBy(['name' => $request->query->get('tag')]);
        }

        $latestPosts = $posts->findLatest($page, $tag);

        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templates.html#template-naming
		error_log(sprintf('<<< Exiting index($request=%s,$page=%s,$_format=%s,$posts=%s,$tags=%s)', is_scalar($request) ? $request : gettype($request), is_scalar($page) ? $page : gettype($page), is_scalar($_format) ? $_format : gettype($_format), is_scalar($posts) ? $posts : gettype($posts), is_scalar($tags) ? $tags : gettype($tags)));
        return $this->render('blog/index.'.$_format.'.twig', [
            'paginator' => $latestPosts,
            'tagName' => $tag?->getName(),
        ]);
    }

    /**
     * NOTE: when the controller argument is a Doctrine entity, Symfony makes an
     * automatic database query to fetch it based on the value of the route parameters.
     * The '{slug:post}' configuration tells Symfony to use the 'slug' route
     * parameter in the database query that fetches the entity of the $post argument.
     * This is mostly useful when the route has multiple parameters and the controller
     * also has multiple arguments.
     * See https://symfony.com/doc/current/doctrine.html#automatically-fetching-objects-entityvalueresolver.
     */
    #[Route('/posts/{slug:post}', name: 'blog_post', requirements: ['slug' => Requirement::ASCII_SLUG], methods: ['GET'])]
    public function postShow(Post $post): Response
    {
		error_log(sprintf('>>> Entering postShow($post=%s)', is_scalar($post) ? $post : gettype($post)));
        // Symfony's 'dump()' function is an improved version of PHP's 'var_dump()' but
        // it's not available in the 'prod' environment to prevent leaking sensitive information.
        // It can be used both in PHP files and Twig templates, but it requires to
        // have enabled the DebugBundle. Uncomment the following line to see it in action:
        //
        // dump($post, $this->getUser(), new \DateTime());
        //
        // The result will be displayed either in the Symfony Profiler or in the stream output.
        // See https://symfony.com/doc/current/profiler.html
        // See https://symfony.com/doc/current/templates.html#the-dump-twig-utilities
        //
        // You can also leverage Symfony's 'dd()' function that dumps and
        // stops the execution

		error_log(sprintf('<<< Exiting postShow($post=%s)', is_scalar($post) ? $post : gettype($post)));
        return $this->render('blog/post_show.html.twig', ['post' => $post]);
    }

    /**
     * NOTE: The #[MapEntity] mapping is required because the route parameter
     * (postSlug) doesn't match any of the Doctrine entity properties (slug).
     *
     * See https://symfony.com/doc/current/doctrine.html#doctrine-entity-value-resolver
     */
    #[Route('/comment/{postSlug}/new', name: 'comment_new', requirements: ['postSlug' => Requirement::ASCII_SLUG], methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function commentNew(
        #[CurrentUser] User $user,
        Request $request,
        #[MapEntity(mapping: ['postSlug' => 'slug'])] Post $post,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
    ): Response {
		error_log(sprintf('>>> Entering commentNew($user=%s,$request=%s,$eventDispatcher=%s,$entityManager=%s)', is_scalar($user) ? $user : gettype($user), is_scalar($request) ? $request : gettype($request), is_scalar($eventDispatcher) ? $eventDispatcher : gettype($eventDispatcher), is_scalar($entityManager) ? $entityManager : gettype($entityManager)));
        $comment = new Comment();
        $comment->setAuthor($user);
        $post->addComment($comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
			error_log(sprintf('commentNew($user,$request,$eventDispatcher,$entityManager)=%s,%s,%s,%s: database query executed in %s ms', is_scalar($user) ? $user : gettype($user), is_scalar($request) ? $request : gettype($request), is_scalar($eventDispatcher) ? $eventDispatcher : gettype($eventDispatcher), is_scalar($entityManager) ? $entityManager : gettype($entityManager), round((microtime(true) - $__logStart) * 1000)));
            $entityManager->flush();

            // When an event is dispatched, Symfony notifies it to all the listeners
            // and subscribers registered to it. Listeners can modify the information
            // passed in the event and they can even modify the execution flow, so
            // there's no guarantee that the rest of this controller will be executed.
            // See https://symfony.com/doc/current/components/event_dispatcher.html
            //
            // If you prefer to process comments asynchronously (e.g. to perform some
            // heavy tasks on them) you can use the Symfony Messenger component.
            // See https://symfony.com/doc/current/messenger.html
            $eventDispatcher->dispatch(new CommentCreatedEvent($comment));

            return $this->redirectToRoute('blog_post', ['slug' => $post->getSlug()], Response::HTTP_SEE_OTHER);
        }

		error_log(sprintf('<<< Exiting commentNew($user=%s,$request=%s,$eventDispatcher=%s,$entityManager=%s)', is_scalar($user) ? $user : gettype($user), is_scalar($request) ? $request : gettype($request), is_scalar($eventDispatcher) ? $eventDispatcher : gettype($eventDispatcher), is_scalar($entityManager) ? $entityManager : gettype($entityManager)));
        return $this->render('blog/comment_form_error.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    /**
     * This controller is called directly via the render() function in the
     * blog/post_show.html.twig template. That's why it's not needed to define
     * a route name for it.
     */
    public function commentForm(Post $post): Response
    {
		error_log(sprintf('>>> Entering commentForm($post=%s)', is_scalar($post) ? $post : gettype($post)));
        $form = $this->createForm(CommentType::class);

		error_log(sprintf('<<< Exiting commentForm($post=%s)', is_scalar($post) ? $post : gettype($post)));
        return $this->render('blog/_comment_form.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'blog_search', methods: ['GET'])]
    public function search(Request $request): Response
    {
		error_log(sprintf('>>> Entering search($request=%s)', is_scalar($request) ? $request : gettype($request)));
		error_log(sprintf('<<< Exiting search($request=%s)', is_scalar($request) ? $request : gettype($request)));
        return $this->render('blog/search.html.twig', ['query' => (string) $request->query->get('q', '')]);
    }
}
