<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "Cases".
 *
 * @property string $cube
 * @property string $subset
 * @property integer $sequence
 * @property string $alias
 * @property string $state
 *
 * @property AlgsForCase[] $algsForCases
 * @property Algs[] $algs
 * @property Subsets $subset0
 * @property Cubes $cube0
 */
class Cases extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Cases';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cube', 'subset', 'sequence', 'state'], 'required'],
            [['sequence'], 'integer'],
            [['cube'], 'string', 'max' => 10],
            [['subset'], 'string', 'max' => 20],
            [['alias'], 'string', 'max' => 50],
            [['state'], 'string', 'max' => 300],
            [['cube', 'subset', 'alias'], 'unique', 'filter' => ['NOT', ['alias' => null]], 'targetAttribute' => ['cube', 'subset', 'alias'], 'message' => 'The combination of Cube, Subset and Alias has already been taken.'],
            [['cube', 'subset'], 'exist', 'skipOnError' => true, 'targetClass' => Subsets::className(), 'targetAttribute' => ['cube' => 'cube', 'subset' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cube' => Yii::t('db', 'Cube'),
            'subset' => Yii::t('db', 'Subset'),
            'sequence' => Yii::t('db', 'Sequence'),
            'alias' => Yii::t('db', 'Alias'),
            'state' => Yii::t('db', 'State'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlgsForCases()
    {
        return $this->hasMany(AlgsForCase::className(), ['cube' => 'cube', 'subset' => 'subset', 'sequence' => 'sequence']);
    }

    public function getAlgs() {
        return $this->hasMany(Algs::className(), ['id' => 'alg'])->via('algsForCases');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubset0()
    {
        return $this->hasOne(Subsets::className(), ['cube' => 'cube', 'name' => 'subset']);
    }

    public function getCube0() {
        return $this->hasOne(Cubes::className(), ['id' => 'cube']);
    }
}
