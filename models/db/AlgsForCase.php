<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "Algs_For_Case".
 *
 * @property string $alg
 * @property string $cube
 * @property string $subset
 * @property integer $sequence
 *
 * @property Algs $alg0
 * @property Cases $cube0
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
            [['alg', 'cube', 'subset', 'sequence'], 'required'],
            [['sequence'], 'integer'],
            [['alg'], 'string', 'max' => 32],
            [['cube'], 'string', 'max' => 10],
            [['subset'], 'string', 'max' => 20],
            [['alg', 'subset'], 'unique', 'targetAttribute' => ['alg', 'subset'], 'message' => 'The combination of Alg and Subset has already been taken.'],
            [['alg'], 'exist', 'skipOnError' => true, 'targetClass' => Algs::className(), 'targetAttribute' => ['alg' => 'id']],
            [['cube', 'subset', 'sequence'], 'exist', 'skipOnError' => true, 'targetClass' => Cases::className(), 'targetAttribute' => ['cube' => 'cube', 'subset' => 'subset', 'sequence' => 'sequence']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alg' => Yii::t('db', 'Alg'),
            'cube' => Yii::t('db', 'Cube'),
            'subset' => Yii::t('db', 'Subset'),
            'sequence' => Yii::t('db', 'Sequence'),
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
    public function getCube0()
    {
        return $this->hasOne(Cases::className(), ['cube' => 'cube', 'subset' => 'subset', 'sequence' => 'sequence']);
    }
}
