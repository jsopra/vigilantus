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
						'label' => 'Cadastro',
						'url' => '#',
						'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->isAdministrador(),
						'items' => array(
                            array(
								'label'=>'Bairros', 
								'url'=> array('/cadastro/bairro/index'),
								'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->isAdministrador(),
							),
                            array(
								'label'=>'Condições de Imóveis', 
								'url'=> array('/cadastro/imovelCondicao/index'),
								'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->isAdministrador(),
							),
							array(
								'label'=>'Tipos de Bairros', 
								'url'=> array('/cadastro/bairroTipo/index'),
								'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->isAdministrador(),
							),
                            array(
								'label'=>'Tipos de Imóveis', 
								'url'=> array('/cadastro/imovelTipo/index'),
								'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->isAdministrador(),
							),
                        ),
					),
                    array(
						'label' => 'Sistema',
						'url' => '#',
						'visible'=>!Yii::app()->user->isGuest,
						'items' => array(
							array(
								'label'=>'Usuários', 
								'url'=> array('/cadastro/usuario/index'),
								'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->isAdministrador(),
							),
                            array(
								'label'=>'Atualizar senha', 
								'url'=> array('/cadastro/usuario/updatePassword'),
								'visible'=>!Yii::app()->user->isGuest,
							),
                        ),
					),
				),
			),
		);
	}
}