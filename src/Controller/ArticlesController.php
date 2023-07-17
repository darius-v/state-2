<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

class ArticlesController extends AbstractController
{
    public function __construct(
//        #[Target('blog_publishing')]
//        private WorkflowInterface $workflow,
        private WorkflowInterface $blogPublishingWorkflow,
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
        // we do not set current state - it has to be null initially. Initial state is set in workflow config.
        // https://stackoverflow.com/questions/54628243/symfony-4-workflow-initial-place-doesnt-work

        $this->articleRepository->save($article, true);

        return new Response(sprintf('Article created. <a href="/article/move-to-review/%d">To review</a>', $article->getId()));
    }

    #[Route(path: '/article/move-to-review/{id}', methods: ['GET'])]
    public function toReview(int $id): Response
    {
        $article = $this->articleRepository->findOneBy(['id' => $id]);

        // Update the currentState on the post
        $this->blogPublishingWorkflow->apply($article, 'to_review');

        return new Response('Article now is in review');
    }
}
