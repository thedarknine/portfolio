<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PagesControllerTest extends WebTestCase
{
    /**
     * Test that homepage is successful.
     */
    public function testHomepageIsSuccessful(): void
    {
        // Create a virtual HTTP client (simulates a browser)
        $client = static::createClient();

        // Execute a GET request on the homepage URL
        $client->request('GET', '/');

        // Assert that the HTTP response is a success (2xx)
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('div.main');
    }

    #[DataProvider('provideSlugsAndTitles')]
    public function testDynamicPagesAreSuccessful(string $slug, string $expectedTagline): array
    {
        $client = static::createClient();
        $client->request('GET', '/'.$slug);

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('.nine-section-label', $expectedTagline);

        return [$slug, $expectedTagline];
    }

    public static function provideSlugsAndTitles(): array
    {
        return [
            'page_experience' => ['experience', 'Construire'],
            'page_competences' => ['competences', 'Maîtriser'],
            'page_formation' => ['formation', 'Apprendre'],
            'page_projets' => ['projets', 'Expérimenter'],
            'page_arcade' => ['arcade', 'Assembler'],
            'page_creations' => ['creations', 'Façonner'],
            'page_photos' => ['photos', 'Observer'],
        ];
    }

    /**
     * Test all unknown slugs return 404.
     */
    public function testUnknownSlugReturns404(): void
    {
        $client = static::createClient();
        $client->request('GET', '/un-slug-qui-n-existe-pas');

        $this->assertResponseStatusCodeSame(404);
    }

    /**
     * Test admin zone is protected and redirects anonymous users.
     */
    public function testAdminZoneIsProtected(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');

        // Check anonymous user is redirected (usually to /login)
        $this->assertResponseRedirects();
    }
}
