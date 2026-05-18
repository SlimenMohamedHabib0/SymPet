<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/commande')]
#[IsGranted('ROLE_ADMIN')]
final class CommandeController extends AbstractController
{
    #[Route(name: 'app_admin_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $repo, Request $request,
                          PaginationService $pagination): Response
    {
        $statut = $request->query->get('statut', '');
        $qb = $repo->createQueryBuilder('c')->orderBy('c.createdAt', 'DESC');
        if ($statut) {
            $qb->where('c.statut = :s')->setParameter('s', $statut);
        }
        $commandes = $pagination->paginate($qb->getQuery(), $request, 10);

        return $this->render('admin/commande/index.html.twig', [
            'commandes' => $commandes,
            'statut'    => $statut,
        ]);
    }




    #[Route('/{id}', name: 'app_admin_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('admin/commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }
    #[Route('/{id}/statut/{statut}', name: 'app_admin_commande_statut', methods: ['POST'])]
    public function changerStatut(Commande $commande, string $statut,
                                  EntityManagerInterface $em, Request $request): Response
    {
        $statutsValides = ['en_attente','en_cours','completee','livree','annulee'];
        if (!in_array($statut, $statutsValides)) {
            $this->addFlash('danger', 'Statut invalide');
            return $this->redirectToRoute('app_admin_commande_index');
        }
        if (!$this->isCsrfTokenValid('statut'.$commande->getId(),
            $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token invalide');
            return $this->redirectToRoute('app_admin_commande_index');
        }
        $commande->setStatut($statut);
        $em->flush();
        $this->addFlash('success', 'Statut mis a jour: '.$statut);
        return $this->redirectToRoute('app_admin_commande_index');
    }





}
