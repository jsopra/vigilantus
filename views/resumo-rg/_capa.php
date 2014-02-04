<div id="capa-resumo-rg" class="row">
    <div class="col-md-6">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th class="number">Geral</th>
                    <th class="number">Área de Foco</th>
                </tr>
            </thead>
            <tbody>
                <tr class="totalizador">
                    <td>Quarteirões</td>
                    <td><?= $resumoTiposImoveis['quarteiroes']['geral'] ?></td>
                    <td><?= $resumoTiposImoveis['quarteiroes']['areasDeFoco'] ?></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th class="number">Geral</th>
                    <th class="number">Área de Foco</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resumoTiposImoveis['tipos_imoveis'] as $label => $values) : ?>
                <tr>
                    <td><?= $label ?></td>
                    <td><?= $values['geral'] ?></td>
                    <td><?= $values['areasDeFoco'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="totalizador">
                    <td>Total</td>
                    <td><?= $resumoTiposImoveis['total']['geral'] ?></td>
                    <td><?= $resumoTiposImoveis['total']['areasDeFoco'] ?></td>
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
                <?php foreach ($resumoBairros as $bairro => $imoveis) : ?>
                <tr>
                    <td><?= $bairro ?></td>
                    <td><?= $imoveis ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>