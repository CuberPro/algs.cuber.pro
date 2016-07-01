<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "Subsets".
 *
 * @property string $cube
 * @property string $name
 * @property string $view
 *
 * @property CasesInSubset[] $casesInSubsets
 * @property Cases[] $cases
 * @property Cubes $cube0
 */
class Subsets extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'Subsets';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['cube', 'name'], 'required'],
            [['cube', 'view'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 50],
            [['cube'], 'exist', 'skipOnError' => true, 'targetClass' => Cubes::className(), 'targetAttribute' => ['cube' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'cube' => Yii::t('db', 'Cube'),
            'name' => Yii::t('db', 'Name'),
            'view' => Yii::t('db', 'View'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCasesInSubsets() {
        return $this->hasMany(CasesInSubset::className(), ['cube' => 'cube', 'subset' => 'name']);
    }

    public function getCases() {
        return $this->hasMany(Cases::className(), ['id' => 'case'])->via('casesInSubsets');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCube0() {
        return $this->hasOne(Cubes::className(), ['id' => 'cube']);
    }
}
