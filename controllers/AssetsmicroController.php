<?php

namespace app\controllers;
use Yii;
use app\models\Assetsmicro;
use app\models\AssetsmicroSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Assets;
use app\models\AssetsSearch;
use app\models\Cartassets;
use app\models\CartassetsSearch;
use app\models\Catagory;
use app\models\CatagorySearch;
use app\models\Assetsuser;
use app\models\Assetsclinical;
use app\models\Assetspathology;
use yii\web\UploadedFile;
use app\models\Personnel;
use app\models\Logtable;
use app\models\Assetsit;
use app\models\Assetssbs;
/**
 * AssetsmicroController implements the CRUD actions for Assetsmicro model.
 */
class AssetsmicroController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Assetsmicro models.
     * @return mixed
     */
   public function actionIndex()
    {
        $searchModel = new AssetsmicroSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder=['id'=>SORT_DESC];
        $dataProvider->pagination->pageSize=1000;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
public function actionIndex2()
    {
        $searchModel = new AssetsmicroSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder=['id'=>SORT_DESC];
        $dataProvider->pagination->pageSize=100000;

        return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
 public function actionIndex1($id)
    {
        $searchModel = new AssetsmicroSearch();
        $dataProvider = $searchModel->search2(Yii::$app->request->queryParams,$id);
        $dataProvider->sort->defaultOrder=['id'=>SORT_DESC];
        //$dataProvider->pagination->pageSize=10;

        return $this->render('index1', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
  public function actionTransferm()
   {
    $searchModel = new AssetsmicroSearch();
    $searchModelOld = new CartassetsSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProviderOld = $searchModelOld->searchm(Yii::$app->request->queryParams);
    $dataProvider->sort->defaultOrder=['id'=>SORT_DESC];
    $dataProviderOld->sort->defaultOrder=['id'=>SORT_DESC];
    $dataProvider->pagination->pageSize=1000;
    $dataProviderOld->pagination->pageSize=1000;

    return $this->render('transferm', [
        'searchModelOld' => $searchModelOld,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'dataProviderOld' => $dataProviderOld,
    ]);
  }
 

    /**
     * Displays a single Assetsmicro model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Assetsmicro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
public function actionCreateas(){
            $model=new Assetsmicro();
              $model->dep='Microlab';
              $model->TD='Microlab';
          if ($model->load(Yii::$app->request->post())) {
     // $model->totalcost=$model->quantity*$model->cost;
          $image = UploadedFile::getInstance($model, 'Picture');
           if (!is_null($image)) {
             $model->Picture = $image->name;
             $ext = end((explode(".", $image->name)));
              // generate a unique file name to prevent duplicate filenames
            //  $model->image_web_filename = Yii::$app->security->generateRandomString().".{$ext}";
             // $model->Picture=$model->image_web_filename;
              // the path to save file, you can set an uploadPath
              // in Yii::$app->params (as used in example below)                       
              Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/';
              $path = Yii::$app->params['uploadPath'] . $model->Picture;
               $image->saveAs($path);
            }
            if ($model->save()) { 
           // if($model->ASSETBARCODE!=NULL){
           //$auto= \app\models\defualtidno::find()->where(['SBS'=>$model->ASSETBARCODE])->one();
          //$auto->active2=1;
           \Yii::$app->session->setFlash('success','Success');
    return $this->redirect(['assetsmicro/index', 'id' => $model->id]);  
            }
             else {
                var_dump ($model->getErrors()); die();
             }
              }
              return $this->render('createas', [
                  'model' => $model,
              ]);     
    }
public function actionCreate2($id,$personnelid,$office,$returnables,$doreturnable)
     {
       //get data from assetsmicro table
        $model =Assetsmicro::find()->where(['id'=>$id])->one();
        $modell =Personnel::find()->where(['personnel.personnelid'=>$personnelid])->one();     
        $add=new Cartassets();
        //$addu=new Assetsuser();
        $addp=new Assetspathology();
        $ada=new Assets();
        $addc=new Assetsclinical();
        $addi=new Assetsit();
        $adds=new Assetssbs();
        $log=new Logtable();
        $log->fullname=Yii::$app->user->identity->fname.' '.Yii::$app->user->identity->mname;
        $log->stockname=$model->NOA;
        $log->action="Transfere";
        $log->dateentered=date('Y-m-d');
     //Copying data
      $ada->monthlyreport=date('m');
      $ada->yearlyreport=date('Y'); 
      $ada->unit=$model->unit;
      $ada->serial=$model->serial;
      $ada->MODEL=$model->MODEL;
      $ada->serviceyear=$model->serviceyear;
      $ada->shelfname=$model->shelfname;
      $ada->shelfno=$model->shelfno;
      $ada->birrformat=$model->birrformat;
      $ada->cost=$model->cost;
      $ada->purchasefrom=$model->purchasefrom;
      $ada->NOA=$model->NOA;
      $ada->DOM=$model->DOM;
      $ada->DOC=$model->DOC;
      $ada->RD=$model->RD;
      $ada->TD=$model->TD;
      $ada->Status=$model->Status;
      $ada->Place=$model->Place;
      $ada->RC=$model->RC;
      $ada->Picture=$model->Picture;
      $ada->RNl=$model->RNl;
      $ada->RM=$model->RM;
      $ada->ASSETID=$model->ASSETID;
      $ada->PARENTASSET_ID=$model->PARENTASSET_ID;
      $ada->ASSETGROUP_ID=$model->ASSETGROUP_ID;
      $ada->LOCATION_ID=$model->LOCATION_ID;
      $ada->VENDOR_ID=$model->VENDOR_ID;
      $ada->OWNER_PERSONNELGROUP_ID=$model->OWNER_PERSONNELGROUP_ID;
      $ada->OWNER_PERSONNEL_ID=$model->OWNER_PERSONNEL_ID;
      $ada->SERVICEAGREEMENT_ID=$model->SERVICEAGREEMENT_ID;
      $ada->CUSTODIAN_PERSONNEL_ID=$model->CUSTODIAN_PERSONNEL_ID;
      $ada->STATUS_ID=$model->STATUS_ID;
      $ada->ASSETBARCODE=$model->ASSETBARCODE;
      $ada->SERIALNUMBER=$model->SERIALNUMBER;
      $ada->DEPARTMENT_ID=$model->DEPARTMENT_ID;
      $ada->CONDITION_ID=$model->CONDITION_ID;
      $ada->SCRAPVALUE=$model->SCRAPVALUE;
      $ada->CURRENTVALUE=$model->CURRENTVALUE;
      $ada->PURCHASEPRICE=$model->PURCHASEPRICE;
      $ada->ACCOUNTCODE=$model->ACCOUNTCODE;
      $ada->PURCHASEORDERNUMBER=$model->PURCHASEORDERNUMBER;
      $ada->ISCHECKEDOUT=$model->ISCHECKEDOUT;
      $ada->ASSETNAME=$model->ASSETNAME;
      $ada->BRAND=$model->BRAND;
      $ada->MANUFACTURER=$model->MANUFACTURER;
      $ada->AUTOBARCODE=$model->AUTOBARCODE;
      $ada->ASSETTYPE=$model->ASSETTYPE;
      $ada->LOCATION=$model->LOCATION;
      $ada->CONDITIONs=$model->CONDITIONs;
      $ada->CUSTODIAN=$model->CUSTODIAN;
      $ada->INCLUDEINAUDIT=$model->INCLUDEINAUDIT;
      $ada->DEPRECIATIONMETHOD=$model->DEPRECIATIONMETHOD;
      $ada->RECOVERYPERIOD=$model->RECOVERYPERIOD;
      $ada->DATEINSERVICE=$model->DATEINSERVICE;
      $ada->DATEAUDITED=$model->DATEAUDITED;
      $ada->DUEDATE=$model->DUEDATE;
      $ada->lotnumber=$model->lotnumber;
      $ada->DATEPURCHASED=$model->DATEPURCHASED;
      $ada->CHECKOUTDATE=$model->CHECKOUTDATE;
      $ada->DATECREATED=$model->DATECREATED;
      $ada->DATEUPDATED=$model->DATEUPDATED;
      $ada->LASTAUDITDATE=$model->LASTAUDITDATE;
      $ada->DATEWARRANTYEXPIRES=$model->DATEWARRANTYEXPIRES;
      $ada->NEXTSERVICEDUEDATE=$model->NEXTSERVICEDUEDATE;
      $ada->NOTES=$model->NOTES;
      $ada->fname=$modell->firstname;
      $ada->lname=$modell->lastname;
      $ada->personnelid=$personnelid;
      $ada->office=$office;
      $ada->dep="Microlab";
      $ada->returnables=$returnables;
      $ada->doreturnable=$doreturnable;
      $ada->username=Yii::$app->user->identity->fname;
     //Copying data
     /* $addu->monthlyreport=date('m');
      $addu->yearlyreport=date('Y'); 
      $addu->unit=$model->unit;
      $addu->serial=$model->serial;
      $addu->MODEL=$model->MODEL;
      $addu->serviceyear=$model->serviceyear;
      $addu->shelfname=$model->shelfname;
      $addu->shelfno=$model->shelfno;
      $addu->birrformat=$model->birrformat;
      $addu->cost=$model->cost;
      $addu->purchasefrom=$model->purchasefrom;
      $addu->NOA=$model->NOA;
      $addu->DOM=$model->DOM;
      $addu->DOC=$model->DOC;
      $addu->RD=$model->RD;
      $addu->TD=$model->TD;
      $addu->Status=$model->Status;
      $addu->Place=$model->Place;
      $addu->RC=$model->RC;
      $addu->Picture=$model->Picture;
      $addu->RNl=$model->RNl;
      $addu->RM=$model->RM;
      $addu->ASSETID=$model->ASSETID;
      $addu->PARENTASSET_ID=$model->PARENTASSET_ID;
      $addu->ASSETGROUP_ID=$model->ASSETGROUP_ID;
      $addu->LOCATION_ID=$model->LOCATION_ID;
      $addu->VENDOR_ID=$model->VENDOR_ID;
      $addu->OWNER_PERSONNELGROUP_ID=$model->OWNER_PERSONNELGROUP_ID;
      $addu->OWNER_PERSONNEL_ID=$model->OWNER_PERSONNEL_ID;
      $addu->SERVICEAGREEMENT_ID=$model->SERVICEAGREEMENT_ID;
      $addu->CUSTODIAN_PERSONNEL_ID=$model->CUSTODIAN_PERSONNEL_ID;
      $addu->STATUS_ID=$model->STATUS_ID;
      $addu->ASSETBARCODE=$model->ASSETBARCODE;
      $addu->SERIALNUMBER=$model->SERIALNUMBER;
      $addu->MODEL=$model->MODEL;
      $addu->DEPARTMENT_ID=$model->DEPARTMENT_ID;
      $addu->CONDITION_ID=$model->CONDITION_ID;
      $addu->SCRAPVALUE=$model->SCRAPVALUE;
      $addu->CURRENTVALUE=$model->CURRENTVALUE;
      $addu->PURCHASEPRICE=$model->PURCHASEPRICE;
      $addu->ACCOUNTCODE=$model->ACCOUNTCODE;
      $addu->PURCHASEORDERNUMBER=$model->PURCHASEORDERNUMBER;
      $addu->ISCHECKEDOUT=$model->ISCHECKEDOUT;
      $addu->ASSETNAME=$model->ASSETNAME;
      $addu->BRAND=$model->BRAND;
      $addu->MANUFACTURER=$model->MANUFACTURER;
      $addu->AUTOBARCODE=$model->AUTOBARCODE;
      $addu->ASSETTYPE=$model->ASSETTYPE;
      $addu->LOCATION=$model->LOCATION;
      $addu->CONDITIONs=$model->CONDITIONs;
      $addu->CUSTODIAN=$model->CUSTODIAN;
      $addu->INCLUDEINAUDIT=$model->INCLUDEINAUDIT;
      $addu->DEPRECIATIONMETHOD=$model->DEPRECIATIONMETHOD;
      $addu->RECOVERYPERIOD=$model->RECOVERYPERIOD;
      $addu->DATEINSERVICE=$model->DATEINSERVICE;
      $addu->DATEAUDITED=$model->DATEAUDITED;
      $addu->DUEDATE=$model->DUEDATE;
      $addu->DATEPURCHASED=$model->DATEPURCHASED;
      $addu->CHECKOUTDATE=$model->CHECKOUTDATE;
      $addu->DATECREATED=$model->DATECREATED;
      $addu->DATEUPDATED=$model->DATEUPDATED;
      $addu->LASTAUDITDATE=$model->LASTAUDITDATE;
      $addu->DATEWARRANTYEXPIRES=$model->DATEWARRANTYEXPIRES;
      $addu->NEXTSERVICEDUEDATE=$model->NEXTSERVICEDUEDATE;
      $addu->NOTES=$model->NOTES;
      $addu->lotnumber=$model->lotnumber;
      $addu->fname=$modell->firstname;
      $addu->lname=$modell->lastname;
      $addu->personnelid=$personnelid;
      $addu->office=$office;
      $addu->dep="Microlab";
      $addu->returnables=$returnables;
      $addu->doreturnable=$doreturnable;
      $addu->username=Yii::$app->user->identity->fname;*/
     //Copying data
      $addp->monthlyreport=date('m');
      $addp->yearlyreport=date('Y'); 
      $addp->unit=$model->unit;
      $addp->serial=$model->serial;
      $addp->MODEL=$model->MODEL;
      $addp->serviceyear=$model->serviceyear;
      $addp->shelfname=$model->shelfname;
      $addp->shelfno=$model->shelfno;
      $addp->birrformat=$model->birrformat;
      $addp->cost=$model->cost;
      $addp->purchasefrom=$model->purchasefrom;
      $addp->NOA=$model->NOA;
      $addp->DOM=$model->DOM;
      $addp->DOC=$model->DOC;
      $addp->RD=$model->RD;
      $addp->TD=$model->TD;
      $addp->Status=$model->Status;
      $addp->Place=$model->Place;
      $addp->RC=$model->RC;
      $addp->Picture=$model->Picture;
      $addp->RNl=$model->RNl;
      $addp->RM=$model->RM;
      $addp->ASSETID=$model->ASSETID;
      $addp->PARENTASSET_ID=$model->PARENTASSET_ID;
      $addp->ASSETGROUP_ID=$model->ASSETGROUP_ID;
      $addp->LOCATION_ID=$model->LOCATION_ID;
      $addp->VENDOR_ID=$model->VENDOR_ID;
      $addp->OWNER_PERSONNELGROUP_ID=$model->OWNER_PERSONNELGROUP_ID;
      $addp->OWNER_PERSONNEL_ID=$model->OWNER_PERSONNEL_ID;
      $addp->SERVICEAGREEMENT_ID=$model->SERVICEAGREEMENT_ID;
      $addp->CUSTODIAN_PERSONNEL_ID=$model->CUSTODIAN_PERSONNEL_ID;
      $addp->STATUS_ID=$model->STATUS_ID;
      $addp->ASSETBARCODE=$model->ASSETBARCODE;
      $addp->SERIALNUMBER=$model->SERIALNUMBER;
      $addp->MODEL=$model->MODEL;
      $addp->DEPARTMENT_ID=$model->DEPARTMENT_ID;
      $addp->CONDITION_ID=$model->CONDITION_ID;
      $addp->SCRAPVALUE=$model->SCRAPVALUE;
      $addp->CURRENTVALUE=$model->CURRENTVALUE;
      $addp->PURCHASEPRICE=$model->PURCHASEPRICE;
      $addp->ACCOUNTCODE=$model->ACCOUNTCODE;
      $addp->PURCHASEORDERNUMBER=$model->PURCHASEORDERNUMBER;
      $addp->ISCHECKEDOUT=$model->ISCHECKEDOUT;
      $addp->ASSETNAME=$model->ASSETNAME;
      $addp->BRAND=$model->BRAND;
      $addp->MANUFACTURER=$model->MANUFACTURER;
      $addp->AUTOBARCODE=$model->AUTOBARCODE;
      $addp->ASSETTYPE=$model->ASSETTYPE;
      $addp->LOCATION=$model->LOCATION;
      $addp->CONDITIONs=$model->CONDITIONs;
      $addp->CUSTODIAN=$model->CUSTODIAN;
      $addp->INCLUDEINAUDIT=$model->INCLUDEINAUDIT;
      $addp->DEPRECIATIONMETHOD=$model->DEPRECIATIONMETHOD;
      $addp->RECOVERYPERIOD=$model->RECOVERYPERIOD;
      $addp->DATEINSERVICE=$model->DATEINSERVICE;
      $addp->DATEAUDITED=$model->DATEAUDITED;
      $addp->DUEDATE=$model->DUEDATE;
      $addp->lotnumber=$model->lotnumber;
      $addp->DATEPURCHASED=$model->DATEPURCHASED;
      $addp->CHECKOUTDATE=$model->CHECKOUTDATE;
      $addp->DATECREATED=$model->DATECREATED;
      $addp->DATEUPDATED=$model->DATEUPDATED;
      $addp->LASTAUDITDATE=$model->LASTAUDITDATE;
      $addp->DATEWARRANTYEXPIRES=$model->DATEWARRANTYEXPIRES;
      $addp->NEXTSERVICEDUEDATE=$model->NEXTSERVICEDUEDATE;
      $addp->NOTES=$model->NOTES;
      $addp->fname=$modell->firstname;
      $addp->lname=$modell->lastname;
      $addp->personnelid=$personnelid;
      $addp->office=$office;
      $addp->dep="Microlab";
      $addp->returnables=$returnables;
      $addp->doreturnable=$doreturnable;
      $addp->username=Yii::$app->user->identity->fname;
     //Copying
      $add->dt=date('Y-m-d');
      $add->monthlyreport=date('m');
      $add->yearlyreport=date('Y'); 
      $add->unit=$model->unit;
      $add->serial=$model->serial;
      $add->MODEL=$model->MODEL;
      $add->serviceyear=$model->serviceyear;
      $add->shelfname=$model->shelfname;
      $add->shelfno=$model->shelfno;
      $add->birrformat=$model->birrformat;
      $add->cost=$model->cost;
      $add->purchasefrom=$model->purchasefrom;
      $add->NOA=$model->NOA;
      $add->DOM=$model->DOM;
      $add->DOC=$model->DOC;
      $add->RD=$model->RD;
      $add->TD=$model->TD;
      $add->Status=$model->Status;
      $add->Place=$model->Place;
      $add->RC=$model->RC;
      $add->Picture=$model->Picture;
      $add->RNl=$model->RNl;
      $add->RM=$model->RM;
      $add->ASSETID=$model->ASSETID;
      $add->PARENTASSET_ID=$model->PARENTASSET_ID;
      $add->ASSETGROUP_ID=$model->ASSETGROUP_ID;
      $add->LOCATION_ID=$model->LOCATION_ID;
      $add->VENDOR_ID=$model->VENDOR_ID;
      $add->OWNER_PERSONNELGROUP_ID=$model->OWNER_PERSONNELGROUP_ID;
      $add->OWNER_PERSONNEL_ID=$model->OWNER_PERSONNEL_ID;
      $add->SERVICEAGREEMENT_ID=$model->SERVICEAGREEMENT_ID;
      $add->CUSTODIAN_PERSONNEL_ID=$model->CUSTODIAN_PERSONNEL_ID;
      $add->STATUS_ID=$model->STATUS_ID;
      $add->ASSETBARCODE=$model->ASSETBARCODE;
      $add->SERIALNUMBER=$model->SERIALNUMBER;
      $add->MODEL=$model->MODEL;
      $add->DEPARTMENT_ID=$model->DEPARTMENT_ID;
      $add->CONDITION_ID=$model->CONDITION_ID;
      $add->SCRAPVALUE=$model->SCRAPVALUE;
      $add->CURRENTVALUE=$model->CURRENTVALUE;
      $add->PURCHASEPRICE=$model->PURCHASEPRICE;
      $add->ACCOUNTCODE=$model->ACCOUNTCODE;
      $add->PURCHASEORDERNUMBER=$model->PURCHASEORDERNUMBER;
      $add->ISCHECKEDOUT=$model->ISCHECKEDOUT;
      $add->ASSETNAME=$model->ASSETNAME;
      $add->BRAND=$model->BRAND;
      $add->lotnumber=$model->lotnumber;
      $add->MANUFACTURER=$model->MANUFACTURER;
      $add->AUTOBARCODE=$model->AUTOBARCODE;
      $add->ASSETTYPE=$model->ASSETTYPE;
      $add->LOCATION=$model->LOCATION;
      $add->CONDITIONs=$model->CONDITIONs;
      $add->CUSTODIAN=$model->CUSTODIAN;
      $add->INCLUDEINAUDIT=$model->INCLUDEINAUDIT;
      $add->DEPRECIATIONMETHOD=$model->DEPRECIATIONMETHOD;
      $add->RECOVERYPERIOD=$model->RECOVERYPERIOD;
      $add->DATEINSERVICE=$model->DATEINSERVICE;
      $add->DATEAUDITED=$model->DATEAUDITED;
      $add->DUEDATE=$model->DUEDATE;
      $add->DATEPURCHASED=$model->DATEPURCHASED;
      $add->CHECKOUTDATE=$model->CHECKOUTDATE;
      $add->DATECREATED=$model->DATECREATED;
      $add->DATEUPDATED=$model->DATEUPDATED;
      $add->LASTAUDITDATE=$model->LASTAUDITDATE;
      $add->DATEWARRANTYEXPIRES=$model->DATEWARRANTYEXPIRES;
      $add->NEXTSERVICEDUEDATE=$model->NEXTSERVICEDUEDATE;
      $add->NOTES=$model->NOTES;
      $add->fname=$modell->firstname;
      $add->lname=$modell->lastname;
      $add->personnelid=$personnelid;
      $add->office=$office;
      $add->dep="Microlab";
      $add->returnables=$returnables;
      $add->doreturnable=$doreturnable;
      $add->username=Yii::$app->user->identity->fname;
     //Copying data
      $addc->monthlyreport=date('m');
      $addc->yearlyreport=date('Y'); 
      $addc->unit=$model->unit;
      $addc->serial=$model->serial;
      $addc->MODEL=$model->MODEL;
      $addc->serviceyear=$model->serviceyear;
      $addc->shelfname=$model->shelfname;
      $addc->shelfno=$model->shelfno;
      $addc->birrformat=$model->birrformat;
      $addc->cost=$model->cost;
      $addc->purchasefrom=$model->purchasefrom;
      $addc->NOA=$model->NOA;
      $addc->DOM=$model->DOM;
      $addc->DOC=$model->DOC;
      $addc->RD=$model->RD;
      $addc->TD=$model->TD;
      $addc->Status=$model->Status;
      $addc->Place=$model->Place;
      $addc->RC=$model->RC;
      $addc->Picture=$model->Picture;
      $addc->RNl=$model->RNl;
      $addc->RM=$model->RM;
      $addc->ASSETID=$model->ASSETID;
      $addc->PARENTASSET_ID=$model->PARENTASSET_ID;
      $addc->ASSETGROUP_ID=$model->ASSETGROUP_ID;
      $addc->LOCATION_ID=$model->LOCATION_ID;
      $addc->VENDOR_ID=$model->VENDOR_ID;
      $addc->OWNER_PERSONNELGROUP_ID=$model->OWNER_PERSONNELGROUP_ID;
      $addc->OWNER_PERSONNEL_ID=$model->OWNER_PERSONNEL_ID;
      $addc->SERVICEAGREEMENT_ID=$model->SERVICEAGREEMENT_ID;
      $addc->CUSTODIAN_PERSONNEL_ID=$model->CUSTODIAN_PERSONNEL_ID;
      $addc->STATUS_ID=$model->STATUS_ID;
      $addc->ASSETBARCODE=$model->ASSETBARCODE;
      $addc->SERIALNUMBER=$model->SERIALNUMBER;
      $addc->MODEL=$model->MODEL;
      $addc->DEPARTMENT_ID=$model->DEPARTMENT_ID;
      $addc->CONDITION_ID=$model->CONDITION_ID;
      $addc->SCRAPVALUE=$model->SCRAPVALUE;
      $addc->CURRENTVALUE=$model->CURRENTVALUE;
      $addc->PURCHASEPRICE=$model->PURCHASEPRICE;
      $addc->ACCOUNTCODE=$model->ACCOUNTCODE;
      $addc->PURCHASEORDERNUMBER=$model->PURCHASEORDERNUMBER;
      $addc->ISCHECKEDOUT=$model->ISCHECKEDOUT;
      $addc->ASSETNAME=$model->ASSETNAME;
      $addc->BRAND=$model->BRAND;
      $addc->MANUFACTURER=$model->MANUFACTURER;
      $addc->AUTOBARCODE=$model->AUTOBARCODE;
      $addc->ASSETTYPE=$model->ASSETTYPE;
      $addc->LOCATION=$model->LOCATION;
      $addc->CONDITIONs=$model->CONDITIONs;
      $addc->CUSTODIAN=$model->CUSTODIAN;
      $addc->INCLUDEINAUDIT=$model->INCLUDEINAUDIT;
      $addc->DEPRECIATIONMETHOD=$model->DEPRECIATIONMETHOD;
      $addc->RECOVERYPERIOD=$model->RECOVERYPERIOD;
      $addc->DATEINSERVICE=$model->DATEINSERVICE;
      $addc->DATEAUDITED=$model->DATEAUDITED;
      $addc->DUEDATE=$model->DUEDATE;
      $addc->DATEPURCHASED=$model->DATEPURCHASED;
      $addc->CHECKOUTDATE=$model->CHECKOUTDATE;
      $addc->DATECREATED=$model->DATECREATED;
      $addc->DATEUPDATED=$model->DATEUPDATED;
      $addc->LASTAUDITDATE=$model->LASTAUDITDATE;
      $addc->DATEWARRANTYEXPIRES=$model->DATEWARRANTYEXPIRES;
      $addc->NEXTSERVICEDUEDATE=$model->NEXTSERVICEDUEDATE;
      $addc->NOTES=$model->NOTES;
      $addc->fname=$modell->firstname;
      $addc->lname=$modell->lastname;
      $addc->personnelid=$personnelid;
      $addc->lotnumber=$model->lotnumber;
      $addc->office=$office;
      $addc->dep="Microlab";
      $addc->returnables=$returnables;
      $addc->doreturnable=$doreturnable;
      $addc->username=Yii::$app->user->identity->fname;
    //copying data
      $addi->monthlyreport=date('m');
      $addi->yearlyreport=date('Y'); 
      $addi->unit=$model->unit;
      $addi->serial=$model->serial;
      $addi->MODEL=$model->MODEL;
      $addi->serviceyear=$model->serviceyear;
      $addi->shelflifedeicide=$model->shelflifedeicide;
      $addi->shelflife=$model->shelflife;
      $addi->shelfname=$model->shelfname;
      $addi->shelfno=$model->shelfno;
      $addi->birrformat=$model->birrformat;
      $addi->cost=$model->cost;
      $addi->purchasefrom=$model->purchasefrom;
      $addi->NOA=$model->NOA;
      $addi->DOM=$model->DOM;
      $addi->DOC=$model->DOC;
      $addi->RD=$model->RD;
      $addi->TD=$model->TD;
      $addi->Status=$model->Status;
      $addi->Place=$model->Place;
      $addi->RC=$model->RC;
      $addi->Picture=$model->Picture;
      $addi->RNl=$model->RNl;
      $addi->RM=$model->RM;
      $addi->ASSETID=$model->ASSETID;
      $addi->PARENTASSET_ID=$model->PARENTASSET_ID;
      $addi->ASSETGROUP_ID=$model->ASSETGROUP_ID;
      $addi->LOCATION_ID=$model->LOCATION_ID;
      $addi->VENDOR_ID=$model->VENDOR_ID;
      $addi->OWNER_PERSONNELGROUP_ID=$model->OWNER_PERSONNELGROUP_ID;
      $addi->OWNER_PERSONNEL_ID=$model->OWNER_PERSONNEL_ID;
      $addi->SERVICEAGREEMENT_ID=$model->SERVICEAGREEMENT_ID;
      $addi->CUSTODIAN_PERSONNEL_ID=$model->CUSTODIAN_PERSONNEL_ID;
      $addi->STATUS_ID=$model->STATUS_ID;
      $addi->ASSETBARCODE=$model->ASSETBARCODE;
      $addi->SERIALNUMBER=$model->SERIALNUMBER;
      $addi->MODEL=$model->MODEL;
      $addi->DEPARTMENT_ID=$model->DEPARTMENT_ID;
      $addi->CONDITION_ID=$model->CONDITION_ID;
      $addi->SCRAPVALUE=$model->SCRAPVALUE;
      $addi->CURRENTVALUE=$model->CURRENTVALUE;
      $addi->PURCHASEPRICE=$model->PURCHASEPRICE;
      $addi->ACCOUNTCODE=$model->ACCOUNTCODE;
      $addi->PURCHASEORDERNUMBER=$model->PURCHASEORDERNUMBER;
      $addi->ISCHECKEDOUT=$model->ISCHECKEDOUT;
      $addi->ASSETNAME=$model->ASSETNAME;
      $addi->BRAND=$model->BRAND;
      $addi->MANUFACTURER=$model->MANUFACTURER;
      $addi->AUTOBARCODE=$model->AUTOBARCODE;
      $addi->ASSETTYPE=$model->ASSETTYPE;
      $addi->LOCATION=$model->LOCATION;
      $addi->CONDITIONs=$model->CONDITIONs;
      $addi->CUSTODIAN=$model->CUSTODIAN;
      $addi->INCLUDEINAUDIT=$model->INCLUDEINAUDIT;
      $addi->DEPRECIATIONMETHOD=$model->DEPRECIATIONMETHOD;
      $addi->RECOVERYPERIOD=$model->RECOVERYPERIOD;
      $addi->DATEINSERVICE=$model->DATEINSERVICE;
      $addi->DATEAUDITED=$model->DATEAUDITED;
      $addi->DUEDATE=$model->DUEDATE;
      $addi->DATEPURCHASED=$model->DATEPURCHASED;
      $addi->CHECKOUTDATE=$model->CHECKOUTDATE;
      $addi->DATECREATED=$model->DATECREATED;
      $addi->DATEUPDATED=$model->DATEUPDATED;
      $addi->LASTAUDITDATE=$model->LASTAUDITDATE;
      $addi->DATEWARRANTYEXPIRES=$model->DATEWARRANTYEXPIRES;
      $addi->NEXTSERVICEDUEDATE=$model->NEXTSERVICEDUEDATE;
      $addi->NOTES=$model->NOTES;
      $addi->fname=$modell->firstname;
      $addi->lname=$modell->lastname;
      $addi->personnelid=$personnelid;
      $addi->office=$office;
      $addi->dep="Microlab";
      $addi->returnables=$returnables;
      $addi->doreturnable=$doreturnable;
      $addi->username=Yii::$app->user->identity->fname;
     //Copying data
      $adds->monthlyreport=date('m');
      $adds->yearlyreport=date('Y'); 
      $adds->unit=$model->unit;
      $adds->serial=$model->serial;
      $adds->MODEL=$model->MODEL;
      $adds->serviceyear=$model->serviceyear;
      $adds->shelflifedeicide=$model->shelflifedeicide;
      $adds->shelflife=$model->shelflife;
      $adds->shelfname=$model->shelfname;
      $adds->shelfno=$model->shelfno;
      $adds->birrformat=$model->birrformat;
      $adds->cost=$model->cost;
      $adds->purchasefrom=$model->purchasefrom;
      $adds->NOA=$model->NOA;
      $adds->DOM=$model->DOM;
      $adds->DOC=$model->DOC;
      $adds->RD=$model->RD;
      $adds->TD=$model->TD;
      $adds->Status=$model->Status;
      $adds->Place=$model->Place;
      $adds->RC=$model->RC;
      $adds->Picture=$model->Picture;
      $adds->RNl=$model->RNl;
      $adds->RM=$model->RM;
      $adds->ASSETID=$model->ASSETID;
      $adds->PARENTASSET_ID=$model->PARENTASSET_ID;
      $adds->ASSETGROUP_ID=$model->ASSETGROUP_ID;
      $adds->LOCATION_ID=$model->LOCATION_ID;
      $adds->VENDOR_ID=$model->VENDOR_ID;
      $adds->OWNER_PERSONNELGROUP_ID=$model->OWNER_PERSONNELGROUP_ID;
      $adds->OWNER_PERSONNEL_ID=$model->OWNER_PERSONNEL_ID;
      $adds->SERVICEAGREEMENT_ID=$model->SERVICEAGREEMENT_ID;
      $adds->CUSTODIAN_PERSONNEL_ID=$model->CUSTODIAN_PERSONNEL_ID;
      $adds->STATUS_ID=$model->STATUS_ID;
      $adds->ASSETBARCODE=$model->ASSETBARCODE;
      $adds->SERIALNUMBER=$model->SERIALNUMBER;
      $adds->MODEL=$model->MODEL;
      $adds->DEPARTMENT_ID=$model->DEPARTMENT_ID;
      $adds->CONDITION_ID=$model->CONDITION_ID;
      $adds->SCRAPVALUE=$model->SCRAPVALUE;
      $adds->CURRENTVALUE=$model->CURRENTVALUE;
      $adds->PURCHASEPRICE=$model->PURCHASEPRICE;
      $adds->ACCOUNTCODE=$model->ACCOUNTCODE;
      $adds->PURCHASEORDERNUMBER=$model->PURCHASEORDERNUMBER;
      $adds->ISCHECKEDOUT=$model->ISCHECKEDOUT;
      $adds->ASSETNAME=$model->ASSETNAME;
      $adds->BRAND=$model->BRAND;
      $adds->MANUFACTURER=$model->MANUFACTURER;
      $adds->AUTOBARCODE=$model->AUTOBARCODE;
      $adds->ASSETTYPE=$model->ASSETTYPE;
      $adds->LOCATION=$model->LOCATION;
      $adds->CONDITIONs=$model->CONDITIONs;
      $adds->CUSTODIAN=$model->CUSTODIAN;
      $adds->INCLUDEINAUDIT=$model->INCLUDEINAUDIT;
      $adds->DEPRECIATIONMETHOD=$model->DEPRECIATIONMETHOD;
      $adds->RECOVERYPERIOD=$model->RECOVERYPERIOD;
      $adds->DATEINSERVICE=$model->DATEINSERVICE;
      $adds->DATEAUDITED=$model->DATEAUDITED;
      $adds->DUEDATE=$model->DUEDATE;
      $adds->DATEPURCHASED=$model->DATEPURCHASED;
      $adds->CHECKOUTDATE=$model->CHECKOUTDATE;
      $adds->DATECREATED=$model->DATECREATED;
      $adds->DATEUPDATED=$model->DATEUPDATED;
      $adds->LASTAUDITDATE=$model->LASTAUDITDATE;
      $adds->DATEWARRANTYEXPIRES=$model->DATEWARRANTYEXPIRES;
      $adds->NEXTSERVICEDUEDATE=$model->NEXTSERVICEDUEDATE;
      $adds->NOTES=$model->NOTES;
      $adds->fname=$modell->firstname;
      $adds->lname=$modell->lastname;
      $adds->personnelid=$personnelid;
      $adds->office=$office;
      $adds->dep="Microlab";
      $adds->returnables=$returnables;
      $adds->doreturnable=$doreturnable;
      //$model->quantity=$model->quantity-$quantity;
     //$model->totalcost=$model->totalcost-$model->cost*$quantity;*/
     //$dup=Cartassets::find()->where(['fkk'=>$id])->one(); 
  if($model->load(Yii::$app->request->post())){
        }
  if($addc->office=="Clinical"){
  if($addc->save()) {
        $add->save();
        $log->save();
$sq="DELETE FROM assetsmicro WHERE id=$id";
        $query=Yii::$app->db->createCommand($sq)->execute();
           \Yii::$app->session->setFlash('success','Success');
    return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]);  
     }
  else{
   \Yii::$app->session->setFlash('error','Not done');
    return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]);  
   }}
if($ada->office=="Admin"){
  if($ada->save()){
             $add->save();
             $log->save();
$sq="DELETE FROM assetsmicro WHERE id=$id";
        $query=Yii::$app->db->createCommand($sq)->execute();
   \Yii::$app->session->setFlash('success','Success');
    return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]);  
       }
else{
   \Yii::$app->session->setFlash('error','Not done');
    return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]);  
   }}
if($addp->office=="Pathology"||$addp->office=="Microlab"){
  if($addp->save()){
             $add->save();
             $log->save();
$sq="DELETE FROM assetsmicro WHERE id=$id";
        $query = Yii::$app->db->createCommand($sq)->execute();
   \Yii::$app->session->setFlash('success','Success');
    return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]);  
       }
else{
   \Yii::$app->session->setFlash('error','Not done');
    return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]);  
   }}
/*if($addu->office=="Microlab"){
  if($addu->save()){
             $add->save();
             $log->save();
$sq="DELETE FROM assetsmicro WHERE id=$id";
        $query = Yii::$app->db->createCommand($sq)->execute();
   \Yii::$app->session->setFlash('success','success');
    return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]);  
         }
else{
   \Yii::$app->session->setFlash('error','Not done');
    return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]);  
   }}*/
  if($addi->office=="IT"){
    if($addi->save()){
             $add->save();
             $log->save();
$sq="DELETE FROM assetsmicro WHERE id=$id";
        $query = Yii::$app->db->createCommand($sq)->execute();
   \Yii::$app->session->setFlash('success','Success');
    return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]);  
       }
else{
   \Yii::$app->session->setFlash('error','Not done');
    return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]);  
   }}
if($adds->office=="SBS"){
  if($adds->save()){
             $add->save();
             $log->save();
$sq="DELETE FROM assetsmicro WHERE id=$id";
        $query = Yii::$app->db->createCommand($sq)->execute();
   \Yii::$app->session->setFlash('success','success');
    return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]);  
         }
else{
   \Yii::$app->session->setFlash('error','Not done');
           return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]); 
   }}
   \Yii::$app->session->setFlash('error','Try');
    return $this->redirect(['assetsmicro/transferm', 'id' => $model->id]);  
       }
public function actionCreate3($id)
          {
            $add=new Assetsmicro();
     $model=Cartassets::find()->where(['id'=>$id])->one();
      $add->catagoryname=$model->catagoryname;
      $add->unit=$model->unit;
      $add->serial=$model->serial;
      $add->MODEL=$model->MODEL;
      $add->serviceyear=$model->serviceyear;
      $add->shelfname=$model->shelfname;
      $add->shelfno=$model->shelfno;
      $add->birrformat=$model->birrformat;
      $add->cost=$model->cost;
      $add->purchasefrom=$model->purchasefrom;
      $add->NOA=$model->NOA;
      $add->DOM=$model->DOM;
      $add->DOC=$model->DOC;
      $add->RD=$model->RD;
      $add->TD=$model->TD;
      $add->Status=$model->Status;
      $add->Place=$model->Place;
      $add->RC=$model->RC;
      $add->Picture=$model->Picture;
      $add->RNl=$model->RNl;
      $add->RM=$model->RM;
      $add->ASSETID=$model->ASSETID;
      $add->PARENTASSET_ID=$model->PARENTASSET_ID;
      $add->ASSETGROUP_ID=$model->ASSETGROUP_ID;
      $add->LOCATION_ID=$model->LOCATION_ID;
      $add->VENDOR_ID=$model->VENDOR_ID;
      $add->OWNER_PERSONNELGROUP_ID=$model->OWNER_PERSONNELGROUP_ID;
      $add->OWNER_PERSONNEL_ID=$model->OWNER_PERSONNEL_ID;
      $add->SERVICEAGREEMENT_ID=$model->SERVICEAGREEMENT_ID;
      $add->CUSTODIAN_PERSONNEL_ID=$model->CUSTODIAN_PERSONNEL_ID;
      $add->STATUS_ID=$model->STATUS_ID;
      $add->ASSETBARCODE=$model->ASSETBARCODE;
      $add->SERIALNUMBER=$model->SERIALNUMBER;
      $add->DEPARTMENT_ID=$model->DEPARTMENT_ID;
      $add->CONDITION_ID=$model->CONDITION_ID;
      $add->SCRAPVALUE=$model->SCRAPVALUE;
      $add->CURRENTVALUE=$model->CURRENTVALUE;
      $add->PURCHASEPRICE=$model->PURCHASEPRICE;
      $add->ACCOUNTCODE=$model->ACCOUNTCODE;
      $add->PURCHASEORDERNUMBER=$model->PURCHASEORDERNUMBER;
      $add->ISCHECKEDOUT=$model->ISCHECKEDOUT;
      $add->ASSETNAME=$model->ASSETNAME;
      $add->BRAND=$model->BRAND;
      $add->MANUFACTURER=$model->MANUFACTURER;
      $add->AUTOBARCODE=$model->AUTOBARCODE;
      $add->ASSETTYPE=$model->ASSETTYPE;
      $add->LOCATION=$model->LOCATION;
      $add->CONDITIONs=$model->CONDITIONs;
      $add->CUSTODIAN=$model->CUSTODIAN;
      $add->INCLUDEINAUDIT=$model->INCLUDEINAUDIT;
      $add->DEPRECIATIONMETHOD=$model->DEPRECIATIONMETHOD;
      $add->RECOVERYPERIOD=$model->RECOVERYPERIOD;
      $add->DATEINSERVICE=$model->DATEINSERVICE;
      $add->DATEAUDITED=$model->DATEAUDITED;
      $add->DUEDATE=$model->DUEDATE;
      $add->DATEPURCHASED=$model->DATEPURCHASED;
      $add->CHECKOUTDATE=$model->CHECKOUTDATE;
      $add->DATECREATED=$model->DATECREATED;
      $add->DATEUPDATED=$model->DATEUPDATED;
      $add->LASTAUDITDATE=$model->LASTAUDITDATE;
      $add->DATEWARRANTYEXPIRES=$model->DATEWARRANTYEXPIRES;
      $add->NEXTSERVICEDUEDATE=$model->NEXTSERVICEDUEDATE;
      $add->NOTES=$model->NOTES;
      $add->fname=$model->fname;
      $add->lname=$model->lname;
      $add->mobile=$model->mobile;
      $add->office=$model->office;
      $add->dep=$model->dep;
      $add->returnables=$model->returnables;
      $add->doreturnable=$model->doreturnable;
      $add->username=Yii::$app->user->identity->fname;
     $log=new Logtable();
        $log->fullname=Yii::$app->user->identity->fname.' '.Yii::$app->user->identity->mname;
        $log->stockname=$model->NOA;
        $log->action="Returned";
        $log->dateentered=date('Y-m-d');
    if($add->dep=="Microlab"){
        //$add->checks=$model->fk;
    if($add->save()) {
          $add->save();
          $log->save();
        $sq="DELETE FROM cartassets WHERE id=$id";
        $query = Yii::$app->db->createCommand($sq)->execute();
           \Yii::$app->session->setFlash('success','Success');
           return $this->redirect(['assetsmicro/transferm', 'id' => $add->id]);
       }
       // $add->save();
        //$model->save();
    \Yii::$app->session->setFlash('error','Not done');
           return $this->redirect(['assetsmicro/transferm', 'id' => $add->id]);
           }}
    /**
     * Updates an existing Assetsmicro model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Assetsmicro model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Assetsmicro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Assetsmicro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Assetsmicro::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
