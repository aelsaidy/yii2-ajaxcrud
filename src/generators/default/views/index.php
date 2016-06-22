<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$controlername = $generator->getControllerID();

echo "<?php\n";
?>
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <div id="ajaxCrudDatatable">
        <?="<?="?>GridView::widget([
            'id'=>'crud-datatable-<?= $controlername ?>',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjaxSettings' => ['options' => ['id' => 'crud-<?= $controlername ?>-pjax']],
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> Yii::t('backend','Create new').' <?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>','class'=>'btn btn-default'])//.
                    //Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    //['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    //'{toggleData}'.
                    //'{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> <?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?> listing',
                'before'=>Yii::t('backend','<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>'),
               'after' => BulkButtonWidget::widget([
                                                'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; ' . Yii::t('backend', 'Delete All'), ["/<?= $controlername ?>/bulk-delete"], [
                                                    "class" => "btn btn-danger btn-xs",
                                                    'role' => 'modal-remote-bulk',
                                                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                                    'data-request-method' => 'post',
                                                    'data-confirm-title' => Yii::t('backend', 'Are you sure?'),
                                                    'data-confirm-message' => Yii::t('backend', 'Are you sure want to delete this item')
                                                ]),
                                            ]) .                        
                        '<div class="clearfix"></div>',
            ]
        ])<?="?>\n"?>
    </div>
</div>


<?='
    
    <?php 
    $this->registerJs("$.fn.modal.Constructor.prototype.enforceFocus = function() {};", \yii\web\View::POS_END);
    Modal::begin([
    "id"=>"ajaxCrudModal",
    "options" => [
        "tabindex" => FALSE // important for Select2 to work properly
    ],
    "footer"=>"",// always need it for jquery plugin
])?>'."\n"?>
<?='<?php Modal::end(); ?>'?>
