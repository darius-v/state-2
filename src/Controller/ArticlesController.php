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
        private readonly WorkflowInterface $blogPublishingWorkflow,
        private readonly ArticleRepository $articleRepository
    ) {
    }

    #[Route(path: '/', name: 'articles', methods: ['GET'])]
    public function list(): Response
    {
        $articles = $this->articleRepository->findAll();

        return $this->render('articles_list.html.twig', ['articles' => $articles]);
    }

    #[Route(path: '/articles/create', name: 'create', methods: ['GET'])]
    public function create(): Response
    {
        $article = new Article();
        $article->setText(rand(1, 100));
        // we do not set current state - it has to be null initially. Initial state is set in workflow config.
        // https://stackoverflow.com/questions/54628243/symfony-4-workflow-initial-place-doesnt-work

        $this->articleRepository->save($article, true);

        return new Response(sprintf('Article created. <a href="/article/review/%d">Mark as reviewed</a>', $article->getId()));
    }

    #[Route(path: '/article/review/{id}', methods: ['GET'])]
    public function markAsReviewed(int $id): Response
    {
        $article = $this->articleRepository->findOneBy(['id' => $id]);

        // Update the currentState on the post. I guess it should now change state to reviewed. But for some reason it doet not
        $this->blogPublishingWorkflow->apply($article, 'mark_as_reviewed');

        return $this->redirect('/');
    }

    #[Route(path: '/article/publish/{id}', methods: ['GET'])]
    public function publish(int $id): Response
    {
        $article = $this->articleRepository->findOneBy(['id' => $id]);

        $this->blogPublishingWorkflow->apply($article, 'publish');

        return new Response('Article now is reviewed');
    }

    #[Route(path: '/article/view/{id}', methods: ['GET'])]
    public function view(int $id): Response
    {
        $article = $this->articleRepository->findOneBy(['id' => $id]);

        return $this->render('view.html.twig', ['article' => $article]);
    }
}
