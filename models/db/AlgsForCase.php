<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "Algs_For_Case".
 *
 * @property string $alg
 * @property string $case
 *
 * @property Algs $alg0
 * @property Cases $case0
 */
class AlgsForCase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Algs_For_Case';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alg', 'case'], 'required'],
            [['alg', 'case'], 'string', 'max' => 32],
            [['alg', 'case'], 'unique', 'targetAttribute' => ['alg', 'case'], 'message' => 'The combination of Alg and Case has already been taken.'],
            [['alg'], 'exist', 'skipOnError' => true, 'targetClass' => Algs::className(), 'targetAttribute' => ['alg' => 'id']],
            [['case'], 'exist', 'skipOnError' => true, 'targetClass' => Cases::className(), 'targetAttribute' => ['case' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alg' => Yii::t('db', 'Alg'),
            'case' => Yii::t('db', 'Case'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlg0()
    {
        return $this->hasOne(Algs::className(), ['id' => 'alg']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCase0()
    {
        return $this->hasOne(Cases::className(), ['id' => 'case']);
    }
}
