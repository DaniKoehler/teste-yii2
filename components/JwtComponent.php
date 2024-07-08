<?php

namespace app\components;

use Yii;

class JwtComponent
{
    private static $algorithm = 'HS256';

    public static function createToken($user)
    {
        $time = time();
        $payload = [
            'iss' => 'http://localhost',
            'aud' => 'http://localhost',
            'iat' => $time,
            'nbf' => $time + 1,
            'exp' => $time + 3600,
            'uid' => $user->id,
        ];

        $token = Yii::$app->jwt->getBuilder()
            ->issuedBy('http://localhost')
            ->permittedFor('http://localhost')
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 1)
            ->expiresAt($time + 3600)
            ->withClaim('uid', $user->id)
            ->getToken(Yii::$app->jwt->getSigner('HS256'), Yii::$app->jwt->getKey());

        return (string) $token;
    }

    public static function validateToken($token)
    {
        try {
            $token = Yii::$app->jwt->getParser()->parse((string) $token);

            return (array) $token->getClaims();
        } catch (\Exception $e) {
            return false;
        }
    }
}