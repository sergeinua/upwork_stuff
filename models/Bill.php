<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill".
 *
 * @property integer $id
 * @property integer $from
 * @property integer $to
 * @property double $amount
 * @property integer $status
 * @property integer $created_at
 * @property integer $transfer_id
 */
class Bill extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to', 'amount', 'status', 'created_at'], 'required'],
            [['from', 'created_at', 'modified_at', 'transfer_id'], 'integer'],
            [['status'], 'string'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
            'amount' => 'Amount',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_at' => 'Modified at',
            'transfer_id' => 'Transfer ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTo()
    {
        return $this->hasOne(User::className(), ['id' => 'to']);
    }

}
