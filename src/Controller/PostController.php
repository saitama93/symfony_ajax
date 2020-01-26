<?php

namespace App\Controller;

use App\Entity\Post;
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
        return $this->json(['code' => 200, 'message' => 'Ca marche !'], 200);
    }
}
