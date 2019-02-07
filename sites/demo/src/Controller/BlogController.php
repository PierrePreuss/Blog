<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);

        //Grace a ArticleRepository $repo, plus besoin de préciser, Comme on appelle index, la variable passera automatiquement

        //$article = $repo->find(12); //Ici pour trouver l'article numéro 12 par exemple
        //$article2 = $repo->findOneByTitle("ici le titre de l\'article"); //Ici pour trouver le titre par exemple

        $articles = $repo->findAll();
        //$articles = $repo->findByTitle("ici le titre de l\'article"); //Ici pour trouver le titre de tous les article correspodant par exemple

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles'=> $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */

    public function show(Article $article){
        //$repo = $this->getDoctrine()->getRepository(Article::class);

        //idem que pour index, plus besoin d'utiliser la ligne du dessus si déclaration dans fonction de ArticleRepository $repo.

        //ce qu'il y a de different, c'est que ici on peut se passer de la ligne ci dessous en remplacant     public function show(ArticleRepository $repo , $id){
        // par public function show(Article $article){
        //$article = $repo->find($id);
        //car grâce à la route, symfony arrive à faire une relation entre l'id et l'article.

        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }
}
