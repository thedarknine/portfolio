<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Form\ContactType;
use App\Service\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\RateLimiter\RateLimiterFactoryInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    public function __construct(
        private PageService $pageService,
        private RateLimiterFactoryInterface $contactFormLimiter,
    ) {
    }

    #[Route('/contact', name: 'app_contact', options: ['sitemap' => true])]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $page = $this->pageService->getActivePageBySlug('contact');

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // Honeypot filled in: act as if the message was sent, but drop it
                if ('' !== trim((string) $form->get('website')->getData())) {
                    $this->addFlash('success', 'Votre message a bien été envoyé, merci !');

                    return $this->redirectToRoute('app_contact');
                }

                if (!$this->contactFormLimiter->create($request->getClientIp())->consume()->isAccepted()) {
                    $this->addFlash('error', 'Trop de messages envoyés en peu de temps, merci de réessayer plus tard.');

                    return $this->render('contact/index.html.twig', [
                        'form'         => $form,
                        'current_page' => $page,
                    ], new Response(status: Response::HTTP_TOO_MANY_REQUESTS));
                }

                $data = $form->getData();

                $email = (new Email())
                    ->from('contact@carolinenoyer.fr')
                    ->to('studio@carolinenoyer.fr')
                    ->replyTo($data['email'])
                    ->subject('Nouveau message de contact : ' . $data['subject'])
                    ->text($data['message'])
                    ->html('<p>' . nl2br(htmlspecialchars($data['message'])) . '</p>');

                $mailer->send($email);

                $this->addFlash('success', 'Votre message a bien été envoyé, merci !');

                return $this->redirectToRoute('app_contact');
            }
            $this->addFlash('error', 'Merci de compléter les champs.');

        }

        return $this->render('contact/index.html.twig', [
            'form'             => $form,
            'current_page'     => $page,
        ]);
    }
}
