<?php

namespace app\controllers;
namespace frontend\controllers;

use app\models\Comentario;
use app\models\ComentarioSearch;
use app\models\Livro;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class LivrosController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }




    /**
     * Displays Catalogo page.
     *
     */
    public function actionCatalogo()
    {
        $livros = Livro::find()
            ->orderBy(['titulo' => SORT_ASC])
            ->all();


        return $this->render('catalogo', ['livros' => $livros]);
    }


    //TODO: Adicionar elementos ao carrinho (Session Storage)
    public function actionCarrinho()
    {
        $session = Yii::$app->session;

        if ($session->isActive){
            $session->open();


        }
    }


    //TODO:Verificar se o user logado já tem o livo adicionado ao favoritos

    /**
     * Displays Catalogo page.
     *
     * @throws NotFoundHttpException
     */
    public function actionDetalhes($id)
    {

        $model = new Comentario();

        //find na base de dados do livro com determinado id
        $livro = Livro::findOne($id);

        //request a BD os comentarios em que tenho id livro = id
        $comentarios = Comentario::find()
            ->where(['id_livro' => $id])
            ->all();


        if($livro != null && $model!= null){
            //return da view detalhes com o livro de acordo com o $id recebido
            return $this->render('detalhes', [
                'livro' => $livro,
                'model' => $model,
                'comentarios' => $comentarios,
            ]);
        }

        //caso determinado livro não seja encontrado é retornado o erro 404 not found
        throw new NotFoundHttpException('O livro não foi encontrado.');
    }

}