<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private const SECRET_KEY = 'this-is-a-very-secret-key-change-in-production-32chars-min';
    private const ALGORITHM = 'HS256';
    private const EXPIRATION = 86400; // 24 hours

    public function generateToken(string $userId): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + self::EXPIRATION;

        $payload = [
            'user_id' => $userId,
            'iat' => $issuedAt,
            'exp' => $expirationTime
        ];

        return JWT::encode($payload, self::SECRET_KEY, self::ALGORITHM);
    }

    public function validateToken(string $token)
    {
        try {
            $decoded = JWT::decode($token, new Key(self::SECRET_KEY, self::ALGORITHM));
            return $decoded;
        } catch (\Exception $e) {
            // Token is expired/invalid
            return null;
        }
    }

    public function getUserIdFromToken(string $token)
    {
        $decoded = $this->validateToken($token);
        
        if ($decoded && isset($decoded->user_id)) {
            return $decoded->user_id;
        }
        
        return null;
    }  
}