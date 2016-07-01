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
            [['cube', 'subset', 'sequence', 'case'], 'unique', 'targetAttribute' => ['cube', 'subset', 'sequence', 'case'], 'message' => 'The combination of Cube, Subset, Case and Sequence has already been taken.'],
            [['cube', 'subset', 'sequence', 'alias'], 'unique', 'targetAttribute' => ['cube', 'subset', 'sequence', 'alias'], 'message' => 'The combination of Cube, Subset, Sequence and Alias has already been taken.'],
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
        return $this->hasOne(Cubes::className(), ['id' => 'cube'])->viaTable('Subsets', ['cube' => 'cube', 'name' => 'subset']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCase0() {
        return $this->hasOne(Cases::className(), ['id' => 'case']);
    }
}
