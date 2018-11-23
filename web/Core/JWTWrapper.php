<?php

namespace Core;
use Firebase\JWT\JWT;

/**
 * Management - JWT Tokens
 */
class JWTWrapper
{
    const KEY = 'hellofreshtoken-fernandohs1500_'; // chave

    /**
     * Generate a new token
     */
    public static function encode(array $options)
    {
        $issuedAt = time();
        $expire = $issuedAt + $options['expiration_sec'];

        $tokenParam = [
            'iat'  => $issuedAt,
            'iss'  => $options['iss'],
            'exp'  => $expire,
            'nbf'  => $issuedAt - 1,
            'data' => $options['userdata']
        ];

        return JWT::encode($tokenParam, self::KEY);
    }

    /**
     * Decode token jwt
     */
    public static function decode($jwt)
    {
        return JWT::decode($jwt, self::KEY, ['HS256']);
    }
}