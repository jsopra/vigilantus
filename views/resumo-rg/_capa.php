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
                <tr>
                    <td>Quarteirões</td>
                    <td>3042</td>
                    <td>2749</td>
                </tr>
                <tr>
                    <th>Residências</th>
                    <td>3042</td>
                    <td>2749</td>
                </tr>
                <tr>
                    <th>Comércios</th>
                    <td>3042</td>
                    <td>2749</td>
                </tr>
                <tr>
                    <th>TB´s</th>
                    <td>3042</td>
                    <td>2749</td>
                </tr>
                <tr>
                    <th>PE´s</th>
                    <td>3042</td>
                    <td>2749</td>
                </tr>
                <tr>
                    <th>Outros</th>
                    <td>3042</td>
                    <td>2749</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <td>3042</td>
                    <td>2749</td>
                </tr>
                <tr>
                    <th>Total Soma 2</th>
                    <td>3042</td>
                    <td>2749</td>
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