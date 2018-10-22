<div class="row">
    <div class="col-xs-3">
    	3
    </div>
    <div class="col-xs-3">
    	<table class="table">
			<thead>
				<tr>
					<th>Nº Imóveis</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<table class="table">
						<thead>
							<tr>
								<?php foreach ($data['imoveis'] as $label => $value) : ?>
									<th><?= $label; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach ($data['imoveis'] as $label => $value) : ?>
									<th><?= $value; ?></th>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				</tr>
			</tbody>
		</table>
    </div>
    <div class="col-xs-1">
    	<table class="table">
			<thead>
				<tr>
					<th>Nº Tubitos Amostras Coletadas</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?= $data['tubitos']; ?></th>
				</tr>
			</tbody>
		</table>
    </div>
    <div class="col-xs-2">
    	2
    </div>
    <div class="col-xs-3">
    	3
    </div>
</div>
<div class="row">
    <div class="col-xs-5">
    	5
    </div>
    <div class="col-xs-1">
    	1
    </div>
    <div class="col-xs-6">
    	6
    </div>
 </div>