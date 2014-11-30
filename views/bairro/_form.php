<?php

use app\models\BairroCategoria;
use app\models\Municipio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\GoogleMapsAPIHelper;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonGroup;

/**
 * @var yii\web\View $this
 * @var app\models\Bairro $model
 * @var yii\widgets\ActiveForm $form
 */
$this->registerJsFile(GoogleMapsAPIHelper::getAPIUrl(false, 'drawing'), ['yii\web\JqueryAsset']);
?>
<div class="bairro-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'bairro_categoria_id')->dropDownList(['' => 'Selecione...'] + BairroCategoria::listData('nome')) ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'nome') ?>
            </div>
        </div>
    
        <div class="row" style="margin-bottom: 10px;">
            
            <div class="col-xs-6">
                <?= ButtonGroup::widget([
                    'buttons' => [
                        Button::widget(['label' => 'Marcar área', 'options' => ['class' => 'glow marcar-area']]),
                    ]
                ]); ?>
                
                <?= ButtonGroup::widget([
                    'buttons' => [
                        Button::widget(['label' => 'Remover marcação', 'options' => ['class' => 'glow remover-marcacao']]),
                    ]
                ]); ?>
            </div>
            
        </div>
    
        <?= Html::error($model, 'coordenadasJson',['class' => 'help-block']); ?>
    
        <div id="map"  style="height: 300px; width: 100%;"></div>
        
        <?= Html::activeHiddenInput($model, 'coordenadasJson'); ?>

		<div class="form-group form-actions">
			<?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
            );
            
            echo Html::a(
                'Cancelar',
                array('/bairro/index'),
                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de bairros')
            );

            ?>
            
       </div>

	<?php ActiveForm::end(); ?>
</div>

<?php
$municipio = \Yii::$app->session->get('user.cliente')->municipio;
$municipio->loadCoordenadas();

$coordenadasBairros = $municipio->getCoordenadasBairros(array($model->id));
?>

<?php
if ($municipio->latitude && $municipio->longitude) :

    $javascript = "
    var map;
    var drawingManager;
    var selectedShape;
    
    var selectionColor = '#4387BF';
    var bairroColor = '#FF0000';
    
    var defaultZoom = 12;
    
    var bairroBoundsObj = new google.maps.LatLngBounds();
    var bairroBounds;
    var mapCenter = new google.maps.LatLng(" . $municipio->latitude . ", " . $municipio->longitude . ");
    ";
        
    if ($model->coordenadasJson) {

        $javascript .= "
        
        bairroBounds = [" . GoogleMapsAPIHelper::jsonToBounds($model->coordenadasJson) ."];
        
        selectedShape = new google.maps.Polygon({
            paths: bairroBounds,
            strokeWeight: 0,
            editable: true,
            fillColor: selectionColor,
            fillOpacity: 0.85
        });
        
        for (i = 0; i < bairroBounds.length; i++)
            bairroBoundsObj.extend(bairroBounds[i]);

        mapCenter = bairroBoundsObj.getCenter();
        defaultZoom = 14;
        ";
    }
    $javascript .= "

    var options = {
        zoom: defaultZoom,
        center: mapCenter,
        mapTypeId: google.maps.MapTypeId.HYBRID,
        disableDefaultUI: true,
        zoomControl: true
    };
    
    var drawingOptions = {
        drawingMode: google.maps.drawing.OverlayType.POLYGON,
        drawingControlOptions: {
            drawingModes: []
        },
        polygonOptions: {
            strokeWeight: 0,
            fillOpacity: 0.85,
            editable: true,
            fillColor: selectionColor,
            zindex: 10
        }
    };

    map = new google.maps.Map(document.getElementById('map'), options);  
    ";   
     
    $qtdeBairrosComCoordenada = count($coordenadasBairros);

    if ($qtdeBairrosComCoordenada > 0) : 

        $i = 0;
        foreach($coordenadasBairros as $bairroDados) : 

            $bairroCoordenada = $bairroDados['coordenadas'];

            $javascript .= "

            var bairroBoundsBairro" . $i . " = [" . GoogleMapsAPIHelper::arrayToBounds($bairroCoordenada) . "];
            var bairroBoundsObjBairro" . $i . " = new google.maps.LatLngBounds();
            
            for (i = 0; i < bairroBoundsBairro" . $i . ".length; i++)
                bairroBoundsObjBairro" . $i . ".extend(bairroBoundsBairro" . $i . "[i]);

            var mapCenterBairro" . $i . " = bairroBoundsObjBairro" . $i . ".getCenter();
        
            var bairroPolygon" . $i . " = new google.maps.Polygon({
                paths: bairroBoundsBairro" . $i . ",
                strokeWeight: 0,
                fillColor: bairroColor,
                fillOpacity: 0.85,
                map: map
            });

            var marker = new google.maps.Marker({
                  position: mapCenterBairro" . $i . ",
                  map: map,
                  title: '" . $bairroDados['nome'] . "'
            });

            ";
            
        endforeach;
    endif;

    if ($model->coordenadasJson) :
        $javascript .= "
        setSelection(selectedShape);
        selectedShape.setMap(map);
        overlayClickListener(selectedShape);
        overlayExitListener(selectedShape);
        ";
    endif;

    $javascript .= "
    
    drawingManager = new google.maps.drawing.DrawingManager(drawingOptions);
    
    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
        if (e.type != google.maps.drawing.OverlayType.MARKER) {

            drawingManager.setDrawingMode(null);
            
            overlayClickListener(e.overlay);

            var newShape = e.overlay;
            newShape.type = e.type;
            
            overlayExitListener(e.overlay);
            
            setSelection(newShape);
            
            coordinatesToInput(newShape.getPath().getArray());
        }
    });
    
    function overlayExitListener(overlay) {
        google.maps.event.addListener(overlay, 'click', function() {
            coordinatesToInput(overlay.getPath().getArray());
            setSelection(overlay);
        });
    }
    
    function overlayClickListener(overlay) {
        google.maps.event.addListener(overlay, 'mouseup', function(event){
            coordinatesToInput(overlay.getPath().getArray());
        });
    }

    google.maps.event.addListener(map, 'click', clearSelection);
 
    $(document).ready(function(){
        
       $('.marcar-area').click(function(e) {
            e.preventDefault();
            startDraw();
           
            $('.remover-marcacao').show();
            $('.marcar-area').hide();
            
            return;
       });
       
       $('.remover-marcacao').click(function(e) {
            e.preventDefault();
           
            if(!selectedShape) {
               alert('Não existe área a remover');
               return true;
            }
           
            $('#bairro-coordenadasjson').val('');
           
            deleteSelectedShape();
           
            $('.remover-marcacao').hide();
            $('.marcar-area').show();
            
            return;
        });
        ";
       
        if ($model->coordenadasJson) :
            $javascript .= "
            $('.remover-marcacao').show();
            $('.marcar-area').hide();
            ";
        else :
            $javascript .= "
            $('.remover-marcacao').hide();
            ";
        endif;

        $javascript .= "
        
        $('button[type=\"submit\"]').click(function(){
            if(selectedShape)
                coordinatesToInput(selectedShape.getPath().getArray());
        });
    });

    function clearSelection() {
        
        if (!selectedShape) 
            return;
        
        selectedShape.setEditable(false);
        //selectedShape = null;
    }
    
    function setSelection(shape) {
        
        clearSelection();
        
        selectedShape = shape;
        shape.setEditable(true);
    }

    function deleteSelectedShape() {
        if(drawingManager)
            drawingManager.setMap(map);
        
        if (selectedShape)
            selectedShape.setMap(null);
    }

    function startDraw() {
        drawingManager.setMap(map);
        drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
    }
    
    function coordinatesToInput(coordinates) {
        $('#bairro-coordenadasjson').val(JSON.stringify(coordinates));
    }
";
$this->registerJs($javascript);
endif;
