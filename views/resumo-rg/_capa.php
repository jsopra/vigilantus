<div id="capa-resumo-rg" class="row">
    <div class="col-md-6">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th class="number">Geral</th>
                </tr>
            </thead>
            <tbody>
                <tr class="totalizador">
                    <td>Quarteirões</td>
                    <td class="text-center"><?= $model->getTotalQuarteiroes() ?></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Imóveis</th>
                    <th class="number">Geral</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model->getImoveisPorTipo() as $tipo => $imoveis) : ?>
                <tr>
                    <td><?= $tipo ?></td>
                    <td class="text-center"><?= $imoveis ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="totalizador">
                    <td>Total</td>
                    <td class="text-center"><?= $model->getTotalImoveis() ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Bairro</th>
                    <th>Imóveis</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model->getImoveisPorBairro() as $bairro => $imoveis) : ?>
                <tr>
                    <td><?= $bairro ?></td>
                    <td style="text-align: center;"><?= $imoveis ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>