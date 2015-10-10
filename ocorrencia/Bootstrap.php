<?php

namespace app\ocorrencia;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $slug = '<slug:([a-z\-]{1,})+\-\w{2}>';
        $rules = [
            $slug => 'ocorrencia/cidade/view',
            "{$slug}/ocorrencias/buscar" => 'ocorrencia/cidade/buscar-ocorrencia',
            "{$slug}/ocorrencias/nova/identificacao" => 'ocorrencia/registrar-ocorrencia/identificacao',
            "{$slug}/ocorrencias/nova/detalhes" => 'ocorrencia/registrar-ocorrencia/detalhes',
            "{$slug}/ocorrencias/nova" => 'ocorrencia/registrar-ocorrencia/index',
            "{$slug}/ocorrencias/<hash>/comprovante" => 'ocorrencia/cidade/comprovante-ocorrencia',
            "{$slug}/ocorrencias/<hash>" => 'ocorrencia/cidade/acompanhar-ocorrencia',
            "{$slug}/ocorrencias/acompanhar" => 'ocorrencia/cidade/acompanhar-ocorrencia',
            "{$slug}/mapa-focos-dengue" => 'ocorrencia/cidade/mapa-focos',
        ];
        $append = false;
        $app->getUrlManager()->addRules($rules, $append);
    }
}
