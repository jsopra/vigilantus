<div class="row">
    <div class="col-xs-3">
    	<table class="table">
			<thead>
				<tr>
					<th>Nº Imóveis Trabalhados por Tipo</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<table class="table">
						<thead>
							<tr>
								<?php foreach ($data['imoveis_por_tipo'] as $label => $value) : ?>
									<th><?= $label; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach ($data['imoveis_por_tipo'] as $label => $value) : ?>
									<th><?= $value; ?></th>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				</tr>
			</tbody>
		</table>
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
    	<table class="table">
			<thead>
				<tr>
					<th>Pendências</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<table class="table">
						<thead>
							<tr>
								<?php foreach ($data['pendencias'] as $label => $value) : ?>
									<th><?= $label; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach ($data['pendencias'] as $label => $value) : ?>
									<th><?= $value; ?></th>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				</tr>
			</tbody>
		</table>
    </div>
    <div class="col-xs-3">
    	<table class="table">
			<thead>
				<tr>
					<th>Nº Depósitos inspecionados por tipo</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<table class="table">
						<thead>
							<tr>
								<?php foreach ($data['depositos_inspecionados'] as $label => $value) : ?>
									<th><?= $label; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach ($data['depositos_inspecionados'] as $label => $value) : ?>
									<th><?= $value; ?></th>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				</tr>
			</tbody>
		</table>
    </div>
</div>
<div class="row">
    <div class="col-xs-5">
    	<table class="table">
			<thead>
				<tr>
					<th>Número de Depósitos</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<table class="table">
						<thead>
							<tr>
								<?php foreach ($data['depositos_tratamento'] as $label => $value) : ?>
									<th><?= $label; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach ($data['depositos_tratamento'] as $label => $value) : ?>
									<?php if ($label == 'Eliminados') : ?>
										<?= $value; ?>
									<?php else : ?>
										<table class="table">
											<thead>
												<tr>
													<?php foreach ($value as $sublabel => $subvalue) : ?>
														<th><?= $sublabel; ?></th>
													<?php endforeach; ?>
												</tr>
											</thead>
											<tbody>
												<tr>
													<?php foreach ($value as $sublabel => $subvalue) : ?>
														<th><?= $subvalue; ?></th>
													<?php endforeach; ?>
												</tr>
											</tbody>
										</table>
									<?php endif; ?>
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
					<th>Adulticida</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<table class="table">
						<thead>
							<tr>
								<?php foreach ($data['adulticida'] as $label => $value) : ?>
									<th><?= $label; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach ($data['adulticida'] as $label => $value) : ?>
									<th><?= $value; ?></th>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				</tr>
			</tbody>
		</table>
    </div>
    <div class="col-xs-6">
    	<table class="table">
			<thead>
				<tr>
					<th>Nº dos quarteirões trabalhados</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?= implode(', ', $data['quarteiroes_trabalhados']); ?></th>
				</tr>
			</tbody>
		</table>
		<br />
		<table class="table">
			<thead>
				<tr>
					<th>Nº dos quarteirões concluídos</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?= implode(', ', $data['quarteiroes_concluidos']); ?></th>
				</tr>
			</tbody>
		</table>
    </div>
 </div>