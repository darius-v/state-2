<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

class ArticlesController extends AbstractController
{
    public function __construct(
//        #[Target('blog_publishing')]
        private WorkflowInterface $workflow,
        private ArticleRepository $articleRepository
    ) {
    }

    #[Route(path: '/articles', name: 'articles', methods: ['GET'])]
    public function list(): Response
    {
        return new Response('Welcome to Latte and Code ');
    }

    #[Route(path: '/articles/create', name: 'create', methods: ['GET'])]
    public function create(): Response
    {
        $article = new Article();
        $article->setText(rand(1, 100));
        $article->setCurrentPlace(Article::STATE_DRAFT);

        $this->articleRepository->save($article, true);

        return new Response('Welcome to Latte and Code ');
    }
}
