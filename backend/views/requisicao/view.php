<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Requisicao */

$this->title = 'Requisição: '. $model->id_requisicao;
$this->params['breadcrumbs'][] = ['label' => 'Requisições', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="requisicao-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Atualizar', ['update', 'id' => $model->id_requisicao], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Eliminar', ['delete', 'id' => $model->id_requisicao], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'ID de requisição',
                'attribute' => 'id_requisicao',
            ],
            [
                'label' => 'Data de levantamento',
                'attribute' => 'dta_levantamento',
            ],
            [
                'label' => 'Data de entrega',
                'attribute' => 'dta_entrega',
            ],
            'estado',
            [
                'label' => 'ID de utilizador',
                'attribute' => 'id_utilizador',
            ],
            [
                'label' => 'Nome de utilizador',
                'attribute' => 'utilizador.user.username',
            ],
            [
                'label' => 'Número de telemóvel',
                'attribute' => 'utilizador.num_telemovel',
            ],
            [
                'label' => 'Biblioteca de Levantamento',
                'attribute' => 'bibLevantamento.nome'
            ]
        ],
    ]) ?>

</div>
