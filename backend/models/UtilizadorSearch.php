<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Utilizador;

/**
 * UtilizadorSearch represents the model behind the search form of `app\models\Utilizador`.
 */
class UtilizadorSearch extends Utilizador
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_utilizador', 'bloqueado', 'id_biblioteca'], 'integer'],
            [['primeiro_nome', 'ultimo_nome', 'numero', 'dta_bloqueado', 'dta_nascimento', 'nif', 'num_telemovel', 'dta_registo', 'foto_perfil'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Utilizador::find();

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
            'id_utilizador' => $this->id_utilizador,
            'bloqueado' => $this->bloqueado,
            'dta_bloqueado' => $this->dta_bloqueado,
            'dta_nascimento' => $this->dta_nascimento,
            'dta_registo' => $this->dta_registo,
            'id_biblioteca' => $this->id_biblioteca,
        ]);

        $query->andFilterWhere(['like', 'primeiro_nome', $this->primeiro_nome])
            ->andFilterWhere(['like', 'ultimo_nome', $this->ultimo_nome])
            ->andFilterWhere(['like', 'numero', $this->numero])
            ->andFilterWhere(['like', 'nif', $this->nif])
            ->andFilterWhere(['like', 'num_telemovel', $this->num_telemovel])
            ->andFilterWhere(['like', 'foto_perfil', $this->foto_perfil]);

        return $dataProvider;
    }
}