<?php

use app\models\Municipio;
use app\models\Bairro;
use \app\models\BairroQuarteirao;
use app\helpers\GoogleMapsAPIHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonGroup;

/**
 * @var yii\web\View $this
 * @var app\models\BairroQuarteirao $model
 * @var yii\widgets\ActiveForm $form
 */
?>


<div class="bairro-quarteirao-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-2">
                <?= $form->field($model, 'numero_quarteirao')->textInput() ?>
            </div>
            <div class="col-xs-2">
                <?= $form->field($model, 'numero_quarteirao_2')->textInput() ?>
            </div>
        </div>
    
        
        <div class="row">
            
            <div class="col-xs-3">
                <?= Html::label($model->getAttributeLabel('coordenadas_area'), 'bairroquarteirao-coordenadas_area', ['class' => 'form-group field-bairroquarteirao-coordenadas_area required']); ?>
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
                array('/bairro-quarteirao/index'),
                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de quarteirões de bairros')
            );

            ?>
            
       </div>

	<?php ActiveForm::end(); ?>

</div>

<?php if($municipio->latitude && $municipio->longitude) : ?>
<script>
    var map;
    var drawingManager;
    var selectedShape;
    
    var bairroColor = '#32CD32';
    var selectionColor = '#4387BF';
    var quarteiraoColor = '#FF0000';
    
    var bairroBouds;
    var bairroBoundsObj = new google.maps.LatLngBounds();
    var bairroPolygon;
    
    var defaultZoom = 13;
    var defaultLat = <?= $municipio->latitude; ?>;
    var defaultLong = <?= $municipio->longitude; ?>;

    var mapCenter = new google.maps.LatLng(defaultLat, defaultLong)
    
    var quarteiraoBoundsObj = new google.maps.LatLngBounds();
    var quarteiraoBounds;

    <?php if($bairro->coordenadas) : ?>

        bairroBounds = [<?= GoogleMapsAPIHelper::arrayToBounds($bairro->coordenadas); ?>];

        bairroPolygon = new google.maps.Polygon({
            paths: bairroBounds,
            strokeWeight: 0,
            fillColor: bairroColor,
            fillOpacity: 0.2,
            zindex: 5
        });
        
        for (i = 0; i < bairroBounds.length; i++)
            bairroBoundsObj.extend(bairroBounds[i]);

        mapCenter = bairroBoundsObj.getCenter();
        defaultZoom = 15;
    
    <?php endif; ?>
        
    <?php if ($model->coordenadasJson) : ?>
        
        quarteiraoBounds = [<?= GoogleMapsAPIHelper::jsonToBounds($model->coordenadasJson); ?>];
        
        selectedShape = new google.maps.Polygon({
            paths: quarteiraoBounds,
            strokeWeight: 0,
            editable: true,
            fillColor: selectionColor,
            fillOpacity: 0.85
        });
        
        for (i = 0; i < quarteiraoBounds.length; i++)
            quarteiraoBoundsObj.extend(quarteiraoBounds[i]);

        mapCenter = quarteiraoBoundsObj.getCenter();
        defaultZoom = 16;
    <?php endif; ?>

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
    
    <?php if($bairro->coordenadas) : ?>
        bairroPolygon.setMap(map);
    <?php endif; ?>
        
        
    <?php 
    $qtdeQuarteiroesComCoordenada = count($coordenadasQuarteiroes);
    if ($qtdeQuarteiroesComCoordenada > 0) : ?>
        
        <?php 
        $i = 0;
        foreach($coordenadasQuarteiroes as $dadosQuarteirao) : ?>
        
            <?php $quarteiraoCoordenada = $dadosQuarteirao['coordenada']; ?>

            var quarteiraoBounds<?= $i; ?> = [<?= GoogleMapsAPIHelper::arrayToBounds($quarteiraoCoordenada); ?>];
            var quarteiraoObj<?= $i; ?> = new google.maps.LatLngBounds();

            var quarteiraoPolygon<?= $i; ?> = new google.maps.Polygon({
                paths: quarteiraoBounds<?= $i; ?>,
                strokeWeight: 0,
                fillColor: quarteiraoColor,
                fillOpacity: 0.85,
                map: map
            });
            
            for (i = 0; i < quarteiraoBounds<?= $i; ?>.length; i++)
                quarteiraoObj<?= $i; ?>.extend(quarteiraoBounds<?= $i; ?>[i]);

            var mapCenterQuarteirao<?= $i; ?> = quarteiraoObj<?= $i; ?>.getCenter();

            var marker = new google.maps.Marker({
                  position: mapCenterQuarteirao<?= $i; ?>,
                  map: map,
                  title: '<?= $dadosQuarteirao['numero']; ?>'
            });

        <?php endforeach; ?>
        
    <?php endif; ?>
        
    <?php if ($model->coordenadasJson) : ?>
        setSelection(selectedShape);
        selectedShape.setMap(map);
        overlayClickListener(selectedShape);
        overlayExitListener(selectedShape);
    <?php endif; ?>
    
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
        google.maps.event.addListener(overlay, "mouseup", function(event){
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
           
            $('#bairroquarteirao-coordenadasjson').val('');
           
            deleteSelectedShape();
           
            $('.remover-marcacao').hide();
            $('.marcar-area').show();
            
            return;
       });
       
        <?php if ($model->coordenadasJson) : ?>
            $('.remover-marcacao').show();
            $('.marcar-area').hide();
        <?php else : ?>
            $('.remover-marcacao').hide();
        <?php endif; ?>
        
        $('button[type="submit"]').click(function(){
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
        $('#bairroquarteirao-coordenadasjson').val(JSON.stringify(coordinates));
    }
</script>
<?php endif; ?>