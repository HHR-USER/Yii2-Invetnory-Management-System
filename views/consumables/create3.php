<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Consumables */

$this->title = '';
//$this->params['breadcrumbs'][] = ['label' => 'Consumables', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumables-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('create3', [
        'model' => $model,
    ]) ?>

</div>
