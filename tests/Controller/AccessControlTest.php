<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Vérifie le contrôle d'accès sans dépendre de la base de données.
 */
final class AccessControlTest extends WebTestCase
{
    /**
     * @return iterable<string, array{string, string}>
     */
    public static function protectedUrls(): iterable
    {
        yield 'accueil' => ['GET', '/'];
        yield 'tâches' => ['GET', '/task'];
        yield 'projets' => ['GET', '/admin/project'];
        yield 'utilisateurs' => ['GET', '/user/index'];
    }

    #[DataProvider('protectedUrls')]
    public function testAnonymousIsRedirectedToLogin(string $method, string $url): void
    {
        $client = static::createClient();
        $client->request($method, $url);

        self::assertResponseRedirects('http://localhost/login');
    }

    public function testLoginPageIsPublic(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
    }

    public function testForgotPasswordPageIsPublic(): void
    {
        $client = static::createClient();
        $client->request('GET', '/reset-password');

        self::assertResponseIsSuccessful();
    }

    public function testUpdateStatusRejectsAnonymousRequests(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/task/update-status',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(['id' => 1, 'status' => 'done'])
        );

        self::assertResponseRedirects('http://localhost/login');
    }
}
