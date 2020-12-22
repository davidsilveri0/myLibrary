<?php

namespace app\controllers;
namespace backend\controllers;

use app\models\Biblioteca;
use app\models\Editora;
use app\models\Autor;
use app\models\Requisicao;
use app\models\RequisicaoLivro;
use app\models\Utilizador;
use common\models\UploadForm;
use Yii;
use app\models\Livro;
use app\models\LivroSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * LivrosController implements the CRUD actions for Livro model.
 */
class LivrosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Livro models.
     * @return mixed
     */
    public function actionIndex()
    {
        $livros = Livro::find()
            ->orderBy(['titulo' => SORT_ASC])
            ->all();

        if(Yii::$app->request->post('Livro')['titulo'] != null) {
            $searchModel = new LivroSearch();
            $livros = $searchModel->procurar(Yii::$app->request->post('Livro')['titulo']);
        }

        $livro = new Livro();

        return $this->render('index', [
            'livros' => $livros,
            'searchModel' => $livro
        ]);
    }

    public function actionRequisitado() {

        $requisicoes = RequisicaoLivro::find()
            ->orderBy('id_livro')
            ->all();

        $requisicoesTerminadas = [];
        foreach ($requisicoes as $requisicao){
            if($requisicao->requisicao->estado == 'Pronta para levantamento' || $requisicao->requisicao->estado == 'Levantada'){
                array_push($requisicoesTerminadas, $requisicao->id_livro);
            }
        }

        $livros = Livro::find()
            ->where(['id_livro' => $requisicoesTerminadas])
            ->all();

        $livro = new Livro();

        return $this->render('index', [
            'livros' => $livros,
            'searchModel' => $livro
        ]);

    }

    /**
     * Displays a single Livro model.
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
     * Creates a new Livro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Livro();

        $editoras = Editora::find()
            ->orderBy(['id_editora' => SORT_ASC])
            ->all();
        $listEditoras = ArrayHelper::map($editoras,'id_editora','designacao');

        $autores = Autor::find()
            ->orderBy(['id_autor' => SORT_ASC])
            ->all();
        $listAutores = ArrayHelper::map($autores,'id_autor','nome_autor');

        $bibliotecas = Biblioteca::find()
            ->orderBy(['id_biblioteca' => SORT_ASC])
            ->all();
        $listBibliotecas = ArrayHelper::map($bibliotecas,'id_biblioteca','nome');

        $modelUpload = new UploadForm();

        if ($model->load(Yii::$app->request->post())) {

            $pasta = 'capas';
            $modelUpload->imageFile = UploadedFile::getInstance($modelUpload, 'imageFile');

            if ($modelUpload->upload($model['isbn'], $pasta)) {
                $model->capa = $modelUpload->imageFile->name;
                $model->save();

                return $this->redirect(['view', 'id' => $model->id_livro]);
            }else{
                Yii::$app->session->setFlash('error', 'Ocorreu um erro ao inserir a capa.');
                return $this->actionCreate();
            }

        }

        return $this->render('create', [
            'model' => $model,
            'editoras' => $listEditoras,
            'autores' => $listAutores,
            'bibliotecas' => $listBibliotecas,
            'modelUpload' => $modelUpload
        ]);
    }

    /**
     * Updates an existing Livro model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $editoras = Editora::find()
            ->orderBy(['id_editora' => SORT_ASC])
            ->all();
        $listEditoras = ArrayHelper::map($editoras,'id_editora','designacao');

        $autores = Autor::find()
            ->orderBy(['id_autor' => SORT_ASC])
            ->all();
        $listAutores = ArrayHelper::map($autores,'id_autor','nome_autor');

        $bibliotecas = Biblioteca::find()
            ->orderBy(['id_biblioteca' => SORT_ASC])
            ->all();
        $listBibliotecas = ArrayHelper::map($bibliotecas,'id_biblioteca','nome');

        $modelUpload = new UploadForm();

        if ($model->load(Yii::$app->request->post())) {

            $pasta = 'capas';
            $modelUpload->imageFile = UploadedFile::getInstance($modelUpload, 'imageFile');

            if ($modelUpload->upload($model['isbn'], $pasta)) {
                $model->capa = $modelUpload->imageFile->name;
                $model->save();

                return $this->redirect(['view', 'id' => $model->id_livro]);
            }else{
                Yii::$app->session->setFlash('error', 'Ocorreu um erro ao inserir a capa.');
                return $this->actionCreate();
            }

        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_livro]);
        }

        return $this->render('update', [
            'model' => $model,
            'editoras' => $listEditoras,
            'autores' => $listAutores,
            'bibliotecas' => $listBibliotecas,
            'modelUpload' => $modelUpload
        ]);
    }

    /**
     * Deletes an existing Livro model.
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
     * Finds the Livro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Livro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Livro::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
