<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Assets */

$this->title = '';
//$this->params['breadcrumbs'][] = ['label' => 'Assets', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assets-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('index', [
        'model'=> $model,
    ]) ?>

</div>
