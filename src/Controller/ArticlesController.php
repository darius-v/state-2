<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

class ArticlesController extends AbstractController
{
    public function __construct(
        private readonly WorkflowInterface $blogPublishingWorkflow,
        private readonly ArticleRepository $articleRepository,
        private readonly EntityManagerInterface $entityManager
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
        // we do not set current state - it has to be null initially. Initial state is set in workflow config in initial_marking field.
        // https://stackoverflow.com/questions/54628243/symfony-4-workflow-initial-place-doesnt-work

        $this->articleRepository->save($article, true);

        return $this->redirectToRoute('articles');
    }

    #[Route(path: '/article/review/{id}', methods: ['GET'])]
    public function markAsReviewed(int $id): Response
    {
        $article = $this->articleRepository->findOneBy(['id' => $id]);

        // Update the currentState on the post. I guess it should now change state to reviewed. But for some reason it doet not
        $this->blogPublishingWorkflow->apply($article, 'mark_as_reviewed');

        $this->entityManager->flush();

        return $this->redirectToRoute('articles');
    }

    #[Route(path: '/article/publish/{id}', methods: ['GET'])]
    public function publish(int $id): Response
    {
        $article = $this->articleRepository->findOneBy(['id' => $id]);

        $this->blogPublishingWorkflow->apply($article, 'publish');

        $this->entityManager->flush();

        return $this->redirectToRoute('articles');
    }

    #[Route(path: '/article/view/{id}', methods: ['GET'])]
    public function view(int $id): Response
    {
        $article = $this->articleRepository->findOneBy(['id' => $id]);

        return $this->render('view.html.twig', ['article' => $article]);
    }
}
