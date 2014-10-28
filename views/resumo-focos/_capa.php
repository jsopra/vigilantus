<br />

<div id="capa-tipo-deposito-focos" class="row">
    <div class="col-md-12">
        <h4>Por Tipo de Depósito</h4>
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="number">Tipo</th>
                <?php foreach ($model->getEspecieTransmissor() as $especie) : ?>
                    <th colspan="2"><?= $especie->nome; ?></th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <th rowspan="2" class="number">&nbsp;</th>
                <?php foreach ($model->getEspecieTransmissor() as $especie) : ?>
                    <th>Focos</th>
                    <th>%</th>
                <?php endforeach; ?>
            </tr>
        </thead>
            <tbody>
                <?php foreach ($model->getTiposDepositos() as $tipo) : ?>
                <tr>
                    <td class="text-center"><?= $tipo->sigla ?></td>
                    <?php foreach ($model->getEspecieTransmissor() as $especie) : ?>
                        <td class="text-center"><?= $model->getQuantidadeFocosTipoDeposito(date('Y'), $especie->id, $tipo->id); ?></td>
                        <td class="text-center"><?= $model->getPercentualFocosTipoDeposito(date('Y'), $especie->id, $tipo->id) ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<br />
<div id="capa-forma-focos" class="row">
    <div class="col-md-12">
        <h4>Por Forma de Foco</h4>
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="number">Tipo</th>
                <?php foreach ($model->getEspecieTransmissor() as $especie) : ?>
                    <th colspan="2"><?= $especie->nome; ?></th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <th rowspan="2" class="number">&nbsp;</th>
                <?php foreach ($model->getEspecieTransmissor() as $especie) : ?>
                    <th>Focos</th>
                    <th>%</th>
                <?php endforeach; ?>
            </tr>
        </thead>
            <tbody>
                <?php foreach ($model->getFormasFoco() as $id => $tipo) : ?>
                <tr>
                    <td class="text-center"><?= $tipo ?></td>
                    <?php foreach ($model->getEspecieTransmissor() as $especie) : ?>
                        <td class="text-center"><?= $model->getQuantidadeFocosFormaFoco(date('Y'), $especie->id, $id); ?></td>
                        <td class="text-center"><?= $model->getPercentualFocosFormaFoco(date('Y'), $especie->id, $id) ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>