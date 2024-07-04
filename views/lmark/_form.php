<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Lmark */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lmark-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ETAA_ID')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>