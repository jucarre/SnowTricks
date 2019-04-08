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
     * @Route("/trick/{id}/{page}", name="show", requirements={"page"="\d+"})
     */
    public function show(Trick $trick, Request $request, EntityManagerInterface $manager, CommentRepository $commentRepo)
    {
        // pagination de commentaire
        $nbCommentOnPage = 5;
        if (!$page = $request->query->get('page')) {
            $page = 1;
        }
        $allComment = $commentRepo->findBy(['trick' => $trick->getId()], ['dateCreation' => 'DESC']);
        $nbPages = ceil(count($allComment) / $nbCommentOnPage)+1;

        if ($page > $nbPages) {
            throw $this->createNotFoundException("cette page n'existe pas");
        }
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
                'comments' => $commentRepo->findBy(['trick' => $trick->getId()], ['dateCreation' => 'DESC'], $nbCommentOnPage, $page-1),
                'nbPages' => $nbPages,
                'currentPage' => $page,
            ]
        );
    }
}
