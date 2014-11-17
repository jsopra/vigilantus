<div class="panel panel-default" style="margin-top: 2.5em;">

	<div class="panel-heading denuncia">
		<h2 style="color: #CC0000; font-weight: bold; line-height: 1.5em;">Denuncie!</h2>
		<p style="line-height: 1.5em; color: #585858; font-size: 1.4em;">
		<font style="font-weight: bold; color: #CC0000; font-size: 1.05em;">Faça sua parte!</font> 
		Sua denúncia será avaliada pela <font style="font-weight: bold; font-size: 1.05em; color: #000;">Prefeitura Municipal</font> 
		e você receberá acesso para <font style="font-weight: bold; color: #000; font-size: 1.05em;">acompanhar</font> a resolução.
		</p>
	</div>

	<div class="panel-body">
		<p class="text-center"><strong>As informações da sua denúncia serão mantidas em sigilo.</strong></p>

		<?= $this->render('_formDenuncia', ['municipio' => $municipio, 'model' => $model]); ?>
	</div>

</div>