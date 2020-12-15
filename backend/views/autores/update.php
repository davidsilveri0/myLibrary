<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\Models\Autor */

$this->title = 'Atualizar Autor: ' . $model->id_autor;
$this->params['breadcrumbs'][] = ['label' => 'Autores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_autor, 'url' => ['view', 'id' => $model->id_autor]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="autor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row rowStyling">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <?= $form->field($model, 'nome_autor')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'id_pais')->dropDownList($paises)->label('País') ?>

            <div class="form-group center">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
            </div>

        </div>
        <div class="col-md-4"></div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
