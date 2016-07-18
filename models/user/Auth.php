<?php

namespace app\models\user;

use Yii;

/**
 * This is the model class for table "Auth".
 *
 * @property integer $user_id
 * @property string $source
 * @property string $source_id
 * @property string $source_name
 *
 * @property Users $user
 */
class Auth extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'Auth';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'source', 'source_id', 'source_name'], 'required'],
            [['user_id'], 'integer'],
            [['source'], 'string', 'max' => 10],
            [['source_id', 'source_name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_id' => Yii::t('db', 'User ID'),
            'source' => Yii::t('db', 'Source'),
            'source_id' => Yii::t('db', 'Source ID'),
            'source_name' => Yii::t('db', 'Source Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
