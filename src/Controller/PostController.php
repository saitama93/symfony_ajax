<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostLike;
use App\Repository\PostLikeRepository;
use App\Repository\PostRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * Permet d'afficher la liste des articles
     * 
     * @param PostRepository $repo
     * @return Response
     * 
     * @Route("/", name="homepage")
     */
    public function index(PostRepository $repo)
    {
        return $this->render('post/index.html.twig', [
            'posts' => $repo->findAll(),
        ]);
    }

    /**
     * Permet de liker ou unliker un article
     *
     * @param Post $post
     * @param ObjectManager $manager
     * @param PostLikeRepository $likeRepo
     * @return Response
     * 
     * @Route("/post/{id}/like", name="post_like")
     */
    public function like(Post $post, ObjectManager $manager, PostLikeRepository $likeRepo): Response
    {

        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        // Si l'utilisateur n'est pas connecté
        if (!$user) return $this->json([
            'code' => 403,
            'message' => "Autorisation refusé !"
        ], 403);

        // Si l'utiliateur connecté et aime un article on supprime le like
        if ($post->isLikeByUser($user)) {
            $like = $likeRepo->findOneBy([
                'post' => $post,
                'user' => $user
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'likes' => $likeRepo->count(['post' => $post])
            ], 200);
        }

        // Si l'utiliateur connecté et n'aime un article on crée le like
        $like = new PostLike();
        $like->setPost($post)
            ->setUser($user);
        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajouté',
            'likes' => $likeRepo->count(['post' => $post])
        ], 200);
    }
}
