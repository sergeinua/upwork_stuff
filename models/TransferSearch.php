<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transfer;

/**
 * TransferSearch represents the model behind the search form about `app\models\Transfer`.
 */
class TransferSearch extends Transfer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'from', 'to', 'created_at'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Transfer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
        ]);

        if(Yii::$app->controller->action->id == 'incoming')
            $query->andFilterWhere(['to' => Yii::$app->user->identity->id]);

        if(Yii::$app->controller->action->id == 'outgoing')
            $query->where(['from' => Yii::$app->user->identity->id]);


        return $dataProvider;
    }
}
