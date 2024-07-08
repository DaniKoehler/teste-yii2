<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\JwtComponent;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public static function tableName()
    {
        return '{{%user}}';
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $decoded = JwtComponent::validateToken($token);
        if ($decoded !== false && isset($decoded['uid'])) {
            return static::findOne($decoded['uid']);
        }
        return null;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['login' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
    }

    public function validateAuthKey($authKey)
    {
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function generateJwtToken()
    {
        return JwtComponent::createToken($this);
    }
}
