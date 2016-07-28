<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "Cases_In_Subset".
 *
 * @property string $cube
 * @property string $subset
 * @property string $case
 * @property integer $sequence
 * @property string $alias
 *
 * @property Subsets $subset0
 * @property Subsets $cube0
 * @property Cases $case0
 */
class CasesInSubset extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'Cases_In_Subset';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['cube', 'subset', 'case', 'sequence'], 'required'],
            [['sequence'], 'integer'],
            [['cube'], 'string', 'max' => 10],
            [['subset', 'alias'], 'string', 'max' => 50],
            [['case'], 'string', 'max' => 32],
            [['cube', 'subset', 'case'], 'unique', 'targetAttribute' => ['cube', 'subset', 'case'], 'message' => 'The combination of Cube, Subset and Case has already been taken.'],
            [['cube', 'subset', 'sequence'], 'unique', 'targetAttribute' => ['cube', 'subset', 'sequence'], 'message' => 'The combination of Cube, Subset and Sequence has already been taken.'],
            [['cube', 'subset', 'alias'], 'unique', 'filter' => ['NOT', ['alias' => null]], 'targetAttribute' => ['cube', 'subset', 'alias'], 'message' => 'The combination of Cube, Subset and Alias has already been taken.'],
            [['cube', 'subset'], 'exist', 'skipOnError' => true, 'targetClass' => Subsets::className(), 'targetAttribute' => ['cube' => 'cube', 'subset' => 'name']],
            [['case'], 'exist', 'skipOnError' => true, 'targetClass' => Cases::className(), 'targetAttribute' => ['case' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'cube' => Yii::t('db', 'Cube'),
            'subset' => Yii::t('db', 'Subset'),
            'case' => Yii::t('db', 'Case'),
            'sequence' => Yii::t('db', 'Sequence'),
            'alias' => Yii::t('db', 'Alias'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubset0() {
        return $this->hasOne(Subsets::className(), ['cube' => 'cube', 'name' => 'subset']);
    }

    public function getCube0() {
        return $this->hasOne(Cubes::className(), ['id' => 'cube'])->via('subset0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCase0() {
        return $this->hasOne(Cases::className(), ['id' => 'case']);
    }
}
