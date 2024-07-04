<?php

use yii\helpers\Html;
use barcode\barcode\BarcodeGenerator as BarcodeGenerator;
/* @var $this yii\web\View */
/* @var $model app\models\Barcodecontrols */

$this->title = '';

?>

<script>
    function printNow(){
        $("#printout").print();
    }
    
    function printArea(divName){
        $(".footer").addClass('hide');
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        console.log(printContents);
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>         
<button class="btn btn-primary pull-right" onclick="printArea('printout')">Print</button>
    <div id="printout">
        <?php 
$name=app\models\Consumables::find()->where(['store'=>Yii::$app->user->identity->Type])->one();
         //$noi=$name->noi;
         foreach ($model as $value) {?>
            <div id="showBarcode_<?= $value->id;?>" class="barcode"></div>
            <?php $optionsArray = array(
            'elementId'=> 'showBarcode_'.$value->id, 
            'value'=> $value->consbarcode, 
            'type'=>'code128',
            );
    if($value->consbarcode!=NULL){
echo BarcodeGenerator::widget($optionsArray).$value->noi;
    }
else{

}}?>
    </div>
<script>
    $(document).ready(function(){
        $(".barcode").removeAttr("style");
        $(".barcode").attr("style", "page-break-after:always;display:block;padding-left: 1px;padding-top: 5px;width: 90px;margin-left: 32px;margin-top:25px;");
    });
</script>

