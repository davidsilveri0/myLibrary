<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Utilizador;
use yii\db\Query;

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
            [['bloqueado', 'id_biblioteca'], 'integer'],
            [['id_utilizador', 'primeiro_nome', 'ultimo_nome', 'numero', 'dta_bloqueado', 'dta_nascimento', 'nif', 'num_telemovel', 'dta_registo', 'foto_perfil'], 'safe'],
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
    public function search($params, $pesquisa = null)
    {

        switch ($pesquisa){
            case 'Bloqueados':$query = Utilizador::find()->where(['bloqueado' => 1])->orderBy('id_utilizador');
                break;

            case 'Desbloqueados':
                $subQueryRole = (new Query())->select('user_id')->from('auth_assignment')->where(['item_name' => 'leitor']);
                $query = Utilizador::find()->where(['id_utilizador' => $subQueryRole, 'bloqueado' => null])->orderBy('id_utilizador');
                break;

            case null:
                $subQueryRole = (new Query())->select('user_id')->from('auth_assignment')->where(['item_name' => 'leitor']);
                $query = Utilizador::find()->where(['id_utilizador' => $subQueryRole])->orderBy('id_utilizador');
                break;

            default: $query = Utilizador::find()->where(['id_utilizador' => $pesquisa]);
        }


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 5],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('user');

        // grid filtering conditions
        $query->andFilterWhere([
            //'id_utilizador' => $this->id_utilizador,
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
            ->andFilterWhere(['like', 'foto_perfil', $this->foto_perfil])
            ->andFilterWhere(['like', 'user.email', $this->id_utilizador]);

        return $dataProvider;
    }
}
