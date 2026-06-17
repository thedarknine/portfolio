<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;

final class ContactControllerTest extends WebTestCase
{
    // GET /contact — Display page
    public function testContactPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    // POST valide — Email sent + flash success + redirection
    public function testValidSubmitSendsEmailAndRedirects(): void
    {
        $client = static::createClient();

        $mailerMock = $this->createMock(MailerInterface::class);
        $mailerMock->expects($this->once())->method('send');
        static::getContainer()->set(MailerInterface::class, $mailerMock);

        $client->request('POST', '/contact', [
            'contact' => [
                'name'     => 'Caroline Noyer',
                'email'    => 'contact@carolinenoyer.fr',
                'subject'  => 'Test automatisé',
                'message'  => 'Ceci est un message de test suffisamment long.',
                'security' => 'mocked-altcha-payload',
            ],
        ]);

        $this->assertResponseRedirects('/contact');
        $client->followRedirect();
        $this->assertSelectorExists('.alert-success');
    }

    // POST without data — Flash error, no redirection
    public function testEmptySubmitShowsErrorFlashAndStaysOnPage(): void
    {
        $client = static::createClient();
        $client->request('POST', '/contact', [
            'contact' => [
                'name'    => '',
                'email'   => '',
                'subject' => '',
                'message' => '',
            ],
        ]);

        $this->assertSelectorExists('.text-nine-red-600');
        // Not 200 (should be 422 for unprocessable entity, since Symfony 6.2 it validates the form before processing)
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // POST invalid email — No redirection, form re-displayed
    public function testInvalidEmailStaysOnPage(): void
    {
        $client = static::createClient();
        $client->request('POST', '/contact', [
            'contact' => [
                'name'    => 'Test',
                'email'   => 'pas-un-email',
                'subject' => 'Sujet',
                'message' => 'Message suffisamment long pour passer.',
            ],
        ]);

        $this->assertSelectorExists('.text-nine-red-600');
        // Not 200 (should be 422 for unprocessable entity, since Symfony 6.2 it validates the form before processing)
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertSelectorNotExists('[data-flash="success"]');
        $this->assertSelectorExists('.text-nine-red-600');
        $this->assertSelectorTextContains('.text-nine-red-600', 'Merci de compléter les champs.');
    }
}
