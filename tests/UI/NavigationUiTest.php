<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\UI;

use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Panther\Client as PantherClient;
use Symfony\Component\Panther\PantherTestCase;

#[Group('UI')]
class NavigationUiTest extends PantherTestCase
{
    private const SELENIUM_URL = 'http://chrome:4444/wd/hub';
    private const SITE_URL     = 'http://engine';

    /**
     * 🖥️ TEST AFFICHAGE BUREAU (Desktop).
     */
    public function testNavigationMenuOnDesktopResolution(): void
    {
        // 1. On crée le client manuel
        $client    = PantherClient::createSeleniumClient(self::SELENIUM_URL, null, self::SITE_URL);
        $webDriver = $client->getWebDriver();

        $client->request('GET', '/');
        $client->getWebDriver()->manage()->window()->setSize(new \Facebook\WebDriver\WebDriverDimension(1280, 800));

        $desktopMenu = $webDriver->findElement(WebDriverBy::cssSelector('nav ul.hidden.lg\\:flex'));
        $this->assertTrue($desktopMenu->isDisplayed(), 'Le menu desktop complet devrait être affiché sur grand écran.');

        // Le bouton burger (la div englobante en lg:hidden) doit être masqué
        $burgerContainer = $webDriver->findElement(WebDriverBy::cssSelector('nav div.lg\\:hidden'));
        $this->assertFalse($burgerContainer->isDisplayed(), 'Le bouton burger devrait être masqué sur grand écran.');

        $client->quit();
    }

    /**
     * 📱 TEST AFFICHAGE MOBILE.
     */
    public function testNavigationMenuOnMobileResolution(): void
    {
        $client    = PantherClient::createSeleniumClient(self::SELENIUM_URL, null, self::SITE_URL);
        $webDriver = $client->getWebDriver();

        $client->request('GET', '/');
        $client->getWebDriver()->manage()->window()->setSize(new \Facebook\WebDriver\WebDriverDimension(375, 667));

        $desktopMenu     = $webDriver->findElement(WebDriverBy::cssSelector('nav ul.hidden.lg\\:flex'));
        $burgerButton    = $webDriver->findElement(WebDriverBy::cssSelector('.nine-navbar-burger'));
        $mobileMenuPanel = $webDriver->findElement(WebDriverBy::cssSelector('.nine-navbar-menu'));

        // 1. Vérification de l'état initial sur mobile
        $this->assertFalse($desktopMenu->isDisplayed(), 'Le menu desktop textuel devrait être masqué sur écran mobile.');
        $this->assertTrue($burgerButton->isDisplayed(), 'Le bouton burger devrait être visible sur écran mobile.');
        $this->assertFalse($mobileMenuPanel->isDisplayed(), 'Le panneau latéral du menu mobile devrait être masqué par défaut.');

        // 2. Simulation du clic sur le bouton burger pour ouvrir le menu
        $burgerButton->click();

        // On laisse le temps au script ou à la classe CSS de s'exécuter
        $client->waitForVisibility('.nine-navbar-menu');

        // 3. Vérification que le menu mobile s'est bien ouvert
        $this->assertTrue($mobileMenuPanel->isDisplayed(), 'Le panneau latéral du menu mobile devrait être visible après le clic sur le bouton burger.');

        $client->quit();
    }
}
