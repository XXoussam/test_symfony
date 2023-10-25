<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PlayerController extends AbstractController
{
    #[Route('/player', name: 'app_player')]
    public function index(): Response
    {
        return $this->render('player/index.html.twig', [
            'controller_name' => 'PlayerController',
        ]);
    }

    #[Route('/showplayers', name: 'app_player_show')]
    public function show(PlayerRepository $repo): Response
    {
        $result = $repo->findAll();
        return $this->render('player/show.html.twig', [
            'response' => $result,
        ]);
    }

    #[Route('/addplayer', name: 'app_player_add')]
    public function add(ManagerRegistry $mr,Request $req): Response
    {
        $p= new Player();
        $form = $this->createForm(PlayerType::class,$p);
        $form->handleRequest($req);

        if($form->isSubmitted()){
            $em = $mr->getManager();
            $em->persist($p);
            $em->flush();
            return $this->redirectToRoute('app_player_show');
        }


        return $this->render('player/addPlayer.html.twig', [
            'form' => $form->createView(),
        ]);


    }

    #[Route('/delete/{id}', name: 'app_player_delete')]
    public function delete(ManagerRegistry $mr,PlayerRepository $repo,int $id): Response
    {
        $em = $mr->getManager();
        $p = $repo->find($id);
        $em->remove($p);
        $em->flush();
        return $this->redirectToRoute('app_player_show');
    }

    #[Route('/update/{id}', name: 'app_player_update')]
    public function update(ManagerRegistry $mr,PlayerRepository $repo,int $id,Request $req): Response
    {
        $p = $repo->find($id);
        $form = $this->createForm(PlayerType::class,$p);
        $form->handleRequest($req);

        if($form->isSubmitted()){
            $em = $mr->getManager();
            $em->persist($p);
            $em->flush();
            return $this->redirectToRoute('app_player_show');
        }
        return $this->render('player/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }





}
