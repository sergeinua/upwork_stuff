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
            [['from', 'to', 'status', 'created_at', 'transfer_id'], 'integer'],
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
            'transfer_id' => 'Transfer ID',
        ];
    }
}
