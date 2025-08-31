<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\Services;

class JWTAuth implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null) {
        $header = $request->getServer('HTTP_AUTHORIZATION');
        if (!$header) {
            return Services::response()
                      ->setJSON(['message' => 'Token Required'])
                      ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $token = explode(' ', $header)[1] ?? null;
        if (!$token) {
            return Services::response()
                      ->setJSON(['message' => 'Token Invalid'])
                      ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        try {
            JWT::decode($token, new Key(getenv('JWT_SECRET'), 'HS256'));
        } catch (\Throwable $e) {
            return Services::response()
                      ->setJSON(['message' => 'Invalid Token'])
                      ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        // Optional: bisa tambahkan logging
    }
}