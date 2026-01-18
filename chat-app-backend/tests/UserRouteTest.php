<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;

class UserRouteTest extends TestCase
{
    private App $app;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->app = require __DIR__ . '/../public/index.php';
        
        // Clear session before each test
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public function testCreateUserSuccess()
    {
        // Create a POST request
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/users');
        
        // Execute the request
        $response = $this->app->handle($request);
        
        // Assert response status
        $this->assertEquals(200, $response->getStatusCode());
        
        // Assert content type
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        
        // Parse and assert response body
        $body = (string) $response->getBody();
        $data = json_decode($body, true);
        
        $this->assertTrue($data['success']);
        $this->assertNotEmpty($data['id']);
        $this->assertStringStartsWith('user_', $data['id']);
        
        // Assert session was set
        $this->assertEquals($data['id'], $_SESSION['user_id']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Clean up: destroy session
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
