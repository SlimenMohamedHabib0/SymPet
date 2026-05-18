<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/avis')]
#[IsGranted('ROLE_ADMIN')]
final class AvisController extends AbstractController
{
    #[Route(name: 'app_admin_avis_index', methods: ['GET'])]
    public function index(AvisRepository $avisRepository,
                          PaginationService $pagination, Request $request): Response
    {
        $query = $avisRepository->createQueryBuilder('a')
            ->orderBy('a.createdAt','DESC')->getQuery();
        $avis = $pagination->paginate($query, $request, 15);
        return $this->render('admin/avis/index.html.twig', ['avis' => $avis]);
    }


}
