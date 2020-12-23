<?php

namespace frontend\controllers;

use app\models\Biblioteca;
use app\models\Livro;
use app\models\RequisicaoLivro;
use Carbon\Carbon;
use Yii;
use app\models\Requisicao;
use app\models\RequisicaoSearch;
use yii\db\Exception;
use yii\db\Query;
use yii\debug\panels\DumpPanel;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RequisicaoController implements the CRUD actions for Requisicao model.
 */
class RequisicaoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionFinalizar()
{
    $model = new Requisicao();

    $bibliotecas = Biblioteca::find()
        ->all();

    $listBib = \yii\helpers\ArrayHelper::map($bibliotecas, 'id_biblioteca', 'nome');

    return $this->render('finalizar', [
        //'searchModel' => $searchModel,
        //'dataProvider' => $dataProvider,
        'model' => $model,
        'bibliotecas' => $listBib
    ]);
}


    /**
     * Lists all Requisicao models.
     * @return mixed
     */
    public function actionIndex()
    {
        //Operador trenário que verifica se o user é guest. Se sim requisições = null, se estiver logado faz a query para as requisições
        /*!Yii::$app->user->isGuest ? $requisicoes = Requisicao::find()->where(['id_utilizador' => Yii::$app->user->identity->id])->
            orderBy(['id_requisicao' =>SORT_DESC])->all() : $requisicoes = null;*/

        $requisicoes = Requisicao::find()->where(['id_utilizador' => Yii::$app->user->id])->orderBy(['id_requisicao' =>SORT_DESC])->all();


        return $this->render('index', ['requisicoes' => $requisicoes]);
    }

    /**
     * Displays a single Requisicao model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Requisicao model.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $carrinho = Yii::$app->session->get('carrinho');

        $postData = Yii::$app->request->post('Requisicao');


        if ($carrinho != null){
            $model = new Requisicao();

            $model->estado = 'A aguardar tratamento';
            $model->id_utilizador = Yii::$app->user->id;
            $model->id_bib_levantamento = $postData['id_bib_levantamento'];

            $total_livros = $this->totalLivrosEmRequisicao() + count($carrinho);
            $num_excluir = abs(($total_livros) - 5);

            if ($total_livros <= 5){
                if ($model->load(Yii::$app->request->post()) && $model->save()) {

                    $this->adicionarRequisicaoLivro($model->id_requisicao, $carrinho);
                    Yii::$app->session->destroy();
                    Yii::$app->session->setFlash('success', 'Obrigado pela sua requisição!');

                    return $this->redirect(['view', 'id' => $model->id_requisicao]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Excedeu o limite de 5 livros em requisição. Por favor, exclua '. $num_excluir .' livro para concluir esta requisição.');
                return $this->redirect(['requisicao/finalizar']);
            }
        }
        Yii::$app->session->setFlash('error', 'Ocorreu um problema ao finalizar a sua requisição. Tente novamente!');
        return $this->redirect(['requisicao/finalizar']);
    }


    public function adicionarRequisicaoLivro($id_requisicao, $carrinho){
        $requisicaoModel = new RequisicaoLivro();

        /*
         * Insere dados na tabela requisicao_livro com recurso à estrutura Yii DAO(Database Access Objects)
         * recomendado quando o objetivo é inserir multiplos objetos
         */
        foreach ($carrinho as $livro) {
            try {
                Yii::$app->db->createCommand()->insert('requisicao_livro', [
                    'id_livro' => $livro->id_livro,
                    'id_requisicao' => $id_requisicao,
                ])->execute();
            } catch (Exception $e) {
            }
        }
    }

    /**
     * Através do id do utilizador com login efetuado determina a quantidade de livros que este tem em requisição
     * Se este nº exceder um total de 5, então o utilizador atingiu o limite de livros em requisição e é negado adicionar este ao carrinho/finalizar requisicao
     */
    public function totalLivrosEmRequisicao()
    {
        //subquery que obtem os dados relativos à requisicao em que se verifique => id utilizador = id utilizador logado e estado da requisicao terminada
        $subQuery = Requisicao::find()
            ->where(['id_utilizador' =>Yii::$app->user->id])
            ->andWhere(['!=', 'estado', 'Terminada']);

        //query responsável por obter a contagem de "requisicao_livro" onde se verfique subquery.id_requisicao = requiscao_livro.id_requisicao
        $totalReq = RequisicaoLivro::find()
            ->innerJoin(['sub' => $subQuery], 'requisicao_livro.id_requisicao = sub.id_requisicao')
            ->count();

        return $totalReq;
    }

    /**
     * Updates an existing Requisicao model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_requisicao]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Requisicao model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Requisicao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Requisicao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Requisicao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
