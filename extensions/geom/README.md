More about geoJSON: http://geojson.org/geojson-spec.html


    public function actionFocosGeojson()
    {
        $model = new GeoJson;
        
        $focos = FocoTransmissor::find()->ativo()->all();
        foreach($focos as $foco) {
                    
            /*
             * Quarteirão
             */
            $quarteirao = $foco->bairroQuarteirao;
            $quarteirao->loadCoordenadas();
            
            $polygon = new Polygon;
            
            foreach($quarteirao->coordenadas as $coordenada) {
                $point = new Point;
                $point->value = $coordenada;
                $polygon->value[] = $point;
                unset($point);
            }
            
            $model->add($polygon);
            unset($polygon);
            
            /*
             * Foco
             */
            $centro = $quarteirao->getCentro();
            
            $polygon = new Polygon;
            
            foreach($quarteirao->coordenadas as $coordenada) {
                $point = new Point;
                $point->value = $coordenada;
                $polygon->value[] = $point;
                unset($point);
            }
            
            $model->add($polygon);
            unset($polygon);
        }        
        return $model->toJSON();
    }