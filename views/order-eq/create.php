<?php

use yii\helpers\Html;

$this->title ='';
?>
<div class="obstetric-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsAddress'=>$modelsAddress,
    ]) ?>

</div>
