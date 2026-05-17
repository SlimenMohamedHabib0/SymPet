<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PaiementController extends AbstractController
{
    #[Route('/paiement/{id}', name: 'app_paiement_payer')]
    public function payer(Commande $commande): Response
    {
        \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => 'Commande SymPet #'.$commande->getNumeroCommande()],
                    'unit_amount' => (int)($commande->getTotal() * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_paiement_success',
                ['id' => $commande->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_panier_voir', [], UrlGeneratorInterface::ABSOLUTE_URL),]);
return $this->redirect($session->url, 303);
}
    #[Route('/paiement/success/{id}', name: 'app_paiement_success')]
    public function success(Commande $commande, EntityManagerInterface $em,
                            PanierService $panier, MailerInterface $mailer): Response
    {
        $commande->setStatut('en_cours');
        $em->flush();
        $contenu = $panier->getContenu();
// envoyer email
        $email = (new TemplatedEmail())
            ->from(new Address('sympet@mailer.com', 'SymPet'))
            ->to($commande->getUser()->getEmail())
            ->subject('Confirmation commande #'.$commande->getNumeroCommande())
            ->htmlTemplate('emails/confirmation_commande.html.twig')
            ->context(['commande' => $commande,'contenu' => $contenu]);
        $mailer->send($email);
        $panier->clear();
        $this->addFlash('success', 'Paiement reussi! Confirmation envoyee par email.');
        return $this->redirectToRoute('app_commande_historique');
    }

}
