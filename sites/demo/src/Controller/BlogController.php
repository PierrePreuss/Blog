<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\RegistrationType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;



class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
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

//    /**
//     * @Route("/", name="home")
//     */
//    public function home(){
//        return $this->render('blog/home.html.twig');
//    }


    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */

    public function form(Article $article =null, Request $request, ObjectManager $manager) {
//        $article = new Article();
        if(!$article){
            $article = new Article();
        }

//        $form = $this->createFormBuilder($article)
//                ->add('title')
//                ->add('content')
//                ->add('image')
//                ->getForm();
//  Plus nécessaire si création de formulaire via 'php bin/console make:form
        // il suffira de faire un lien avec le formulaire crée automatiquement

        $form = $this->createForm(ArticleType::class, $article);


        $form ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }


        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }



    /**
     * @Route("/blog/{id}", name="blog_show")
     */

    public function show(Article $article, Request $request, ObjectManager $manager){
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime())
                ->setArticle($article);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }
        //$repo = $this->getDoctrine()->getRepository(Article::class);

        //idem que pour index, plus besoin d'utiliser la ligne du dessus si déclaration dans fonction de ArticleRepository $repo.

        //ce qu'il y a de different, c'est que ici on peut se passer de la ligne ci dessous en remplacant     public function show(ArticleRepository $repo , $id){
        // par public function show(Article $article){
        //$article = $repo->find($id);
        //car grâce à la route, symfony arrive à faire une relation entre l'id et l'article.

        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }



}
