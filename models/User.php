<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property integer $created_at
 * @property integer $modified_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'modified_at'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['password', 'authKey'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'authKey' => 'Auth Key',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }

    public static function findIdentity($id){

        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){

        throw new NotSupportedException();//I don't implement this method because I don't have any access token column in my database
    }

    public function getId(){

        return $this->id;
    }

    public function getAuthKey(){

        return $this->authKey;//Here I return a value of my authKey column
    }

    public function validateAuthKey($authKey){

        return $this->authKey === $authKey;
    }

    public static function findByUsername($username){

        return self::findOne(['username'=>$username]);
    }

    public function validatePassword($password){

        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public function getBalance(){

        return $this->hasOne(Balance::className(), ['user_id' => 'id']);
    }
}
