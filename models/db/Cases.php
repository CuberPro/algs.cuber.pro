<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "Cases".
 *
 * @property string $id
 * @property string $state
 *
 * @property AlgsForCase[] $algsForCases
 * @property Algs[] $algs
 * @property CasesInSubset[] $casesInSubsets
 */
class Cases extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'Cases';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'state'], 'required'],
            [['id'], 'string', 'max' => 32],
            [['state'], 'string', 'max' => 300],
            [['state'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('db', 'ID'),
            'state' => Yii::t('db', 'State'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlgsForCases() {
        return $this->hasMany(AlgsForCase::className(), ['case' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlgs() {
        return $this->hasMany(Algs::className(), ['id' => 'alg'])->via('algsForCases');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCasesInSubsets() {
        return $this->hasMany(CasesInSubset::className(), ['case' => 'id']);
    }

    public function getSubsets() {
        return $this->hasMany(Subsets::className(), ['cube' => 'cube', 'name' => 'subset'])->via('casesInSubsets');
    }

    public function getCube() {
        return $this->hasOne(Cubes::className(), ['id' => 'cube'])->via('casesInSubsets');
    }
}
