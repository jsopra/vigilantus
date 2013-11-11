<?php
/**
 * Gerencia layout comum 
 */
class Layout {
	
	/**
	 * Busca o menu do topo
	 * 
	 * @return array 
	 */
	public static function getMenu()
	{
		return array( 
			array(
				'class'=>'bootstrap.widgets.TbMenu',
				'htmlOptions'=>array('class'=>'pull-right'),
				'items'=> array(
					array('label'=>'Entrar', 'url'=>array('/default/session/login'), 'visible'=>Yii::app()->user->isGuest, 'itemOptions' => array('class' => 'loginlogout login')),
					array('label'=>'Sair (' . Yii::app()->user->name . ')', 'url'=>array('/default/session/logout'), 'visible'=>!Yii::app()->user->isGuest, 'itemOptions' => array('class' => 'logged'), 'icon' => 'icon-off')
				)
			),
			array( 
				'class'=>'bootstrap.widgets.TbMenu',
				'htmlOptions'=>array('class'=>'pull-left'),
				'items'=> array(
					array( 
						'label' => 'Gestão de Notícias',
						'url' => '#',
						'visible'=>!Yii::app()->user->isGuest,
						'items' => array(
							array(
								'label'=>'Feeds', 
								'items'=> array(
									array('label'=>'Gerenciar', 'url'=> array('/gerenciador/fonte/index'),'visible'=>!Yii::app()->user->isGuest),
									array('label'=>'Importar de Excel', 'url'=> array('/gerenciador/importaFonte/index'),'visible'=>!Yii::app()->user->isGuest),
								)
							),
							array(
								'label'=>'Noticias', 
								'url'=> array('/gerenciador/fonteItem/index'),
								'visible'=>!Yii::app()->user->isGuest,
							),
						),
					),
					array(
						'label' => 'Tweet',
						'url' => '#',
						'visible'=>!Yii::app()->user->isGuest,
						'url'=> array('/tweetad/mensagem/index'),
						/*
						array(
							'label'=>'Categorias', 
							'url'=> array('/tweetad/categoria/index'),
							'visible'=>!Yii::app()->user->isGuest,
						),
						*/
					),
					array(
						'label' => 'Monitoramento',
						'url' => '#',
						'visible'=>!Yii::app()->user->isGuest,
						'url'=> array('/monitor/report/index'),
					),
					array(
						'label' => 'Interno',
						'url' => '#',
						'visible'=>!Yii::app()->user->isGuest,
						'items' => array(
							array(
								'label'=>'Processamento', 
								'url'=> array('/monitor/processamento/index'),
								'visible'=>!Yii::app()->user->isGuest,
							),
							array(
								'label'=>'Fontes', 
								'url'=> array('/monitor/contaExterna/index'),
								'visible'=>!Yii::app()->user->isGuest,
							),
						),
					),
					array(
						'label'=>'Tags', 
						'url'=> array('/gerenciador/tag/index'),
						'visible'=>!Yii::app()->user->isGuest,
					),
					array(
						'label' => 'Conta',
						'url' => '#',
						'visible'=>!Yii::app()->user->isGuest,
						'items' => array(
							array(
								'label'=>'Contas', 
								'url'=> array('/gerenciador/conta/index'),
								'visible'=>!Yii::app()->user->isGuest,
							),
							array(
								'label'=>'Grupos', 
								'url'=> array('/gerenciador/grupo/index'),
								'visible'=>!Yii::app()->user->isGuest,
							),
						),
					),
					array(
						'label'=>'Configurações', 
						'url'=> array('/default/configuracao/index'),
						'visible'=>!Yii::app()->user->isGuest,
					),
                    array(
						'label' => 'Feedhits',
						'url' => '#',
						'visible'=>!Yii::app()->user->isGuest,
						'items' => array(
							array(
								'label'=>'AD', 
								'url'=> array('/feedhits/aD/index'),
								'visible'=>!Yii::app()->user->isGuest,
							),
                        ),
					),
				),
			),
		);
	}
}