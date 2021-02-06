<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BibliotecaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Catálogo - ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Bibliotecas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biblioteca-index">
    <h1 class="topicos">Catálogo - <?= Html::encode($model->nome)?></h1>
    <hr>


    <?php if($livros != null) { ?>
        <?php foreach ($livros as $livro) { ?>
            <div class="col-xs-12 col-md-2 catalogo-grid gridLivros" style="height: auto; width: 210px; margin: 10px">
                <div class="capa text-center" style="margin-top: 10px">
                    <a href="<?= Url::to(['livros/view', 'id' => $livro->id_livro]) ?>">
                        <?= Html::img(Yii::$app->request->baseUrl . '/imgs/capas/' . $livro->capa, ['id'=> 'imgCapa', 'style' => 'width: 140px; height: 220px;'])?>
                    </a>
                </div>
                <div class="book-info text-center" style="height: 150px; display: block">
                    <h4 id="titulo" style="letter-spacing: 1px"><?= Html::encode($livro->titulo)?></h4>
                    <h5>de <?= Html::encode($livro->autor->nome_autor)?></h5>
                    <h6><?= Html::encode($livro->genero)?></h6>
                    <h6>Idioma: <?= Html::encode($livro->idioma)?></h6>
                    <h6>Formato: <?= Html::encode($livro->formato)?></h6>
                </div>

                <div class="text-center">
                    <?= Html::a('<span class="fas fa-eye"></span>', ['livros/view', 'id' => $livro->id_livro], [
                        'class' => 'btn book-buttons', 'style' => 'color: black'
                    ])?>
                    <?= Html::a('<span class="fas fa-trash-alt"></span>', ['delete', 'id' => $livro->id_livro], [
                        'class' => 'btn  book-buttons',
                        'style' => 'color: black',
                        'data' => [
                            'confirm' => 'Tem a certeza que pretende eliminar este livro?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        <?php }
    } else { ?>
        <br/>
        <p>Parece que não foram encontrados livros.</p>
    <?php } ?>


</div>
