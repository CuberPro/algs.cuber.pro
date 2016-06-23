<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "Cubes".
 *
 * @property string $id
 * @property string $name
 * @property integer $size
 *
 * @property Subsets[] $subsets
 */
class Cubes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Cubes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'size'], 'required'],
            [['size'], 'integer'],
            [['id'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'name' => Yii::t('db', 'Name'),
            'size' => Yii::t('db', 'Size'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubsets()
    {
        return $this->hasMany(Subsets::className(), ['cube' => 'id']);
    }
}
