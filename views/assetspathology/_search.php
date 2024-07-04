<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AssetspathologySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assetspathology-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'catagoryname') ?>

    <?= $form->field($model, 'quantity') ?>

    <?= $form->field($model, 'NOA') ?>

    <?= $form->field($model, 'DOM') ?>

    <?php // echo $form->field($model, 'DOC') ?>

    <?php // echo $form->field($model, 'RD') ?>

    <?php // echo $form->field($model, 'TD') ?>

    <?php // echo $form->field($model, 'Status') ?>

    <?php // echo $form->field($model, 'Place') ?>

    <?php // echo $form->field($model, 'RC') ?>

    <?php // echo $form->field($model, 'Picture') ?>

    <?php // echo $form->field($model, 'RNl') ?>

    <?php // echo $form->field($model, 'RM') ?>

    <?php // echo $form->field($model, 'ASSETID') ?>

    <?php // echo $form->field($model, 'PARENTASSET_ID') ?>

    <?php // echo $form->field($model, 'ASSETGROUP_ID') ?>

    <?php // echo $form->field($model, 'LOCATION_ID') ?>

    <?php // echo $form->field($model, 'VENDOR_ID') ?>

    <?php // echo $form->field($model, 'OWNER_PERSONNELGROUP_ID') ?>

    <?php // echo $form->field($model, 'OWNER_PERSONNEL_ID') ?>

    <?php // echo $form->field($model, 'SERVICEAGREEMENT_ID') ?>

    <?php // echo $form->field($model, 'CUSTODIAN_PERSONNEL_ID') ?>

    <?php // echo $form->field($model, 'STATUS_ID') ?>

    <?php // echo $form->field($model, 'ASSETBARCODE') ?>

    <?php // echo $form->field($model, 'SERIALNUMBER') ?>

    <?php // echo $form->field($model, 'MODEL') ?>

    <?php // echo $form->field($model, 'DEPARTMENT_ID') ?>

    <?php // echo $form->field($model, 'CONDITION_ID') ?>

    <?php // echo $form->field($model, 'SCRAPVALUE') ?>

    <?php // echo $form->field($model, 'CURRENTVALUE') ?>

    <?php // echo $form->field($model, 'PURCHASEPRICE') ?>

    <?php // echo $form->field($model, 'ACCOUNTCODE') ?>

    <?php // echo $form->field($model, 'PURCHASEORDERNUMBER') ?>

    <?php // echo $form->field($model, 'ISCHECKEDOUT') ?>

    <?php // echo $form->field($model, 'ASSETNAME') ?>

    <?php // echo $form->field($model, 'BRAND') ?>

    <?php // echo $form->field($model, 'MANUFACTURER') ?>

    <?php // echo $form->field($model, 'AUTOBARCODE') ?>

    <?php // echo $form->field($model, 'FIRSTNAME') ?>

    <?php // echo $form->field($model, 'LASTNAME') ?>

    <?php // echo $form->field($model, 'PRBS') ?>

    <?php // echo $form->field($model, 'ASSETTYPE') ?>

    <?php // echo $form->field($model, 'LOCATION') ?>

    <?php // echo $form->field($model, 'VENDOR') ?>

    <?php // echo $form->field($model, 'CONDITIONs') ?>

    <?php // echo $form->field($model, 'CUSTODIAN') ?>

    <?php // echo $form->field($model, 'INCLUDEINAUDIT') ?>

    <?php // echo $form->field($model, 'DEPRECIATIONMETHOD') ?>

    <?php // echo $form->field($model, 'RECOVERYPERIOD') ?>

    <?php // echo $form->field($model, 'DATEINSERVICE') ?>

    <?php // echo $form->field($model, 'DATEAUDITED') ?>

    <?php // echo $form->field($model, 'DUEDATE') ?>

    <?php // echo $form->field($model, 'DATEPURCHASED') ?>

    <?php // echo $form->field($model, 'CHECKOUTDATE') ?>

    <?php // echo $form->field($model, 'DATECREATED') ?>

    <?php // echo $form->field($model, 'DATEUPDATED') ?>

    <?php // echo $form->field($model, 'LASTAUDITDATE') ?>

    <?php // echo $form->field($model, 'DATEWARRANTYEXPIRES') ?>

    <?php // echo $form->field($model, 'NEXTSERVICEDUEDATE') ?>

    <?php // echo $form->field($model, 'NOTES') ?>

    <?php // echo $form->field($model, 'fname') ?>

    <?php // echo $form->field($model, 'lname') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'office') ?>

    <?php // echo $form->field($model, 'username') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
