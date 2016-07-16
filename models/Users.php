<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "Users".
 *
 * @property integer $id
 * @property string $name
 * @property string $password
 * @property string $email
 * @property string $created
 * @property integer $status
 */
class Users extends ActiveRecord implements IdentityInterface {

    const AUTH_KEY_PREFIX = 'algs_users_auth_key_';

    const STATUS_ACTIVATED = 0;
    const STATUS_NEEDS_CONFIRM = 1;
    const STATUS_BANNED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'Users';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'password', 'email', 'status'], 'required'],
            [['created'], 'safe'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['password', 'email'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('db', 'ID'),
            'name' => Yii::t('db', 'Name'),
            'password' => Yii::t('db', 'Password'),
            'email' => Yii::t('db', 'Email'),
            'created' => Yii::t('db', 'Created'),
            'status' => Yii::t('db', 'Status'),
        ];
    }

    protected function getAuthKeyCacheKey() {
        return self::AUTH_KEY_PREFIX . $this->id;
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {

    }

    public function getId() {
        return $this->id;
    }

    public function getAuthKey() {
        $authKey = Yii::$app->security->generateRandomString();
        $cache = Yii::$app->cache;
        $cachedAuthKeys = $cache->get($this->authKeyCacheKey);
        if (!$cachedAuthKeys) {
            $cachedAuthKeys = [];
        }
        $now = time();
        foreach ($cachedAuthKeys as $key => $expire) {
            if ($expire < $now) {
                unset($cachedAuthKeys[$key]);
            }
        }
        $cachedAuthKeys[$authKey] = $now + Yii::$app->params['user.rememberLoginTime'];
        $cache->set($this->authKeyCacheKey, $cachedAuthKeys, Yii::$app->params['user.rememberLoginTime']);
        return $authKey;
    }

    public function validateAuthKey($authKey) {
        $cache = Yii::$app->cache;
        $cachedAuthKeys = $cache->get($this->authKeyCacheKey);
        if (!$cachedAuthKeys) {
            return false;
        }
        $now = time();
        foreach ($cachedAuthKeys as $key => $expire) {
            if ($key === $authKey && $expire > $now) {
                return true;
            }
        }
        return false;
    }
}
