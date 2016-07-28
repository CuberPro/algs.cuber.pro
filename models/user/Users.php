<?php

namespace app\models\user;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "Users".
 *
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string $role
 * @property string $wcaid
 * @property string $created
 * @property integer $status
 *
 * @property Auth[] $auths
 */
class Users extends ActiveRecord implements IdentityInterface {

    const AUTH_KEY_PREFIX = 'algs_users_auth_key_';

    const STATUS_ACTIVATED = 0;
    const STATUS_NEEDS_CONFIRM = 1;
    const STATUS_BANNED = 2;

    const EMPTY_PASSWORD = '######';

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
            [['email', 'name', 'password', 'status'], 'required'],
            [['created'], 'safe'],
            [['status'], 'integer'],
            [['email', 'password'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 100],
            [['role', 'wcaid'], 'string', 'max' => 10],
            [['email'], 'unique'],
            [['wcaid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('db', 'ID'),
            'email' => Yii::t('db', 'Email'),
            'name' => Yii::t('db', 'Name'),
            'password' => Yii::t('db', 'Password'),
            'wcaid' => Yii::t('db', 'WCA ID'),
            'role' => Yii::t('db', 'Role'),
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
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuths() {
        return $this->hasMany(Auth::className(), ['user_id' => 'id']);
    }
}
