<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Multa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="multa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'montante')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_requisicao')->textInput() ?>

    <?= $form->field($model, 'dta_multa')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
