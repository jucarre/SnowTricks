<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TrickRepository $trickRepo)
    {
        return $this->render('home/index.html.twig', [
                'tricks' => $trickRepo->findBy([], ['dateCreation' => 'DESC'], 15, 0),
            ]
        );
    }

    /**
     * @Route("/{trick<\d+>?15}", name="load_more_trick")
     */
    public function loadMoreTrick(TrickRepository $trickRepo, $trick = 15)
    {
        return $this->render('home/load_more.html.twig', [
                'tricks' => $trickRepo->findBy([], ['dateCreation' => 'DESC'], 15, $trick),
            ]
        );
    }

    /**
     * @Route("/trick/{id}/{page?<\d+>1}", name="show")
     */
    public function show(Trick $trick, Request $request, EntityManagerInterface $manager, CommentRepository $commentRepo, $page = 1)
    {
        // pagination de commentaire
        $limit = 5;
        if(is_null($page) || $page < 1) {
            $page = 1;
        }

        if(!$allComment = $commentRepo->findBy(['trick' => $trick->getId()], ['dateCreation' => 'DESC'])){
            $nbPages = NULL;
        }
        $nbComment = count($allComment);
        $nbPages = ceil($nbComment / $limit);

        if ($page > $nbPages & $nbPages) {
            throw $this->createNotFoundException("cette page n'existe pas");
        }

        $query = $commentRepo->findAllCommentTrick($page, $limit, $trick);

        // Traiment de formulaire pour les commentaire
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setTrick($trick)
                ->setUser($this->getUser());


            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre commentaire a bien été ajouté !'
            );

            return $this->redirectToRoute('show', [
                    'id' => $trick->getId(),
                ]
            );
        }


        return $this->render('home/trick.html.twig', [
                'form' => $form->createView(),
                'trick' => $trick,
                'comments' => $query,
                'nbPages' => $nbPages,
                'currentPage' => $page,
            ]
        );
    }
}
