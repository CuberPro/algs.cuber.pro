<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "Algs".
 *
 * @property string $id
 * @property string $text
 *
 * @property AlgsForCase[] $algsForCases
 * @property Cases[] $cases
 */
class Algs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Algs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'text'], 'required'],
            [['id'], 'string', 'max' => 32],
            [['text'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'text' => Yii::t('db', 'Text'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlgsForCases()
    {
        return $this->hasMany(AlgsForCase::className(), ['alg' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCases()
    {
        return $this->hasMany(Cases::className(), ['id' => 'case'])->viaTable('Algs_For_Case', ['alg' => 'id']);
    }
}
