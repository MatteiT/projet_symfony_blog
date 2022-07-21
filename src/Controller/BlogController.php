<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

  #[Route('/', name: 'homepage')]
  public function index(): Response
  {
    return $this->render("/blog/index.html.twig");
  }

  #[Route('/add', name: 'article_add')]
  public function add(Request $request, ManagerRegistry $doctrine): Response
  {
    $article = new Article();
    $form = $this->createForm(ArticleType::class, $article);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $article->setLastUpdateDate(new \DateTime());

      if ($article->getPicture() !== null) {
        $file = $form->get("picture")->getData();
        $filename  = uniqid() . '.' . $file->guessExtension();

        try {
          $file->move(
            $this->getParameter("images_directory"), // le dossier dans lequel on va charger l'image
            $filename
          );
        } catch (FileException $e) {
          return new Response($e->getMessage());
        }

        $article->setPicture($filename);
      }

      if ($article->getIsPublished()) {
        $article->setPublicationDate(new \DateTime());
      }

      // on récupère l'entity manager
      $em = $doctrine->getManager();
      $em->persist($article);
      $em->flush(); // exécute la requête

      return new Response("L'article a bien été enregistré");
    }

    return $this->render("/blog/add.html.twig", [
      "form" => $form->createView()
    ]);
  }

  #[Route('/show/{id}', name: 'article_show')]
  public function show($id): Response
  {
    return $this->render("/blog/show.html.twig", [
      "id" => $id
    ]);
  }

  #[Route('/edit/{id}', name: 'article_edit')]
  public function edit($id, ManagerRegistry $doctrine, Article $article): Response
  {
    // "vieille méthod"e :récupérer l'article correspondant à l'id
    // $article = $doctrine->getRepository(Article::class)->find($id);

    $form = $this->createForm(ArticleType::class, $article);

    return $this->render("/blog/edit.html.twig", [
      "article" => $article,
      "form" => $form->createView()
    ]);
  }

  #[Route('/remove/{id}', name: 'article_remove')]
  public function remove($id): Response
  {
    return new Response("<h1>Suppression de l'article $id</h1>");
  }
}
