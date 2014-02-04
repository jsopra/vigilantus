<div id="capa-resumo-rg" class="row">
    <div class="col-md-6">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Resumo</th>
                    <th>Geral</th>
                    <th>Área de Foco</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resumoTiposImoveis as $label => list($geral, $areasDeFoco)) : ?>
                <tr>
                    <td><?= $label ?></td>
                    <td><?= $geral ?></td>
                    <td><?= $areasDeFoco ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
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