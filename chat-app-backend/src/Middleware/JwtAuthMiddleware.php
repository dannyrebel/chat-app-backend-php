<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use App\Service\JwtService;
use Slim\Psr7\Response as SlimResponse;

class JwtAuthMiddleware{
    private $jwtService;
    
    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function __invoke(Request $request, RequestHandler $handler){
      $authHeader = $request->getHeaderLine('Authorization');
      if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'No token provided'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        $token = $matches[1];

        $decoded = $this->jwtService->validateToken($token);

        if (!$decoded) {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode([
            'success' => false,
            'error' => 'Invalid or expired token'
        ]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
        }

        $request = $request->withAttribute('user_id', $decoded->user_id);

        return $handler->handle($request);
    }

}