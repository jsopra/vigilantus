<?php
/**
 * Gerencia layout comum 
 */
class Layout {
	
	private static $_niveis = array();
	
	/**
	 * Busca o menu do topo
	 * 
	 * @return array 
	 */
	public static function getMenu($tags, $tagsSelecionadas, $selecionada = null)
	{
		$tagsFilhasDe = null;
		
		echo '<ul nivel="1" id="navmenu" principal="">';
		foreach($tags as $tag) {
			
			if(count($tag['filhos']) && $selecionada && $selecionada == $tag['nome']) {
				$tagsFilhasDe = $tag['nome'];
			
				echo '<li class="active"><a href="' . Yii::app()->getBaseUrl(true) . '/' . $tag['nome'] . '">' . $tag['nome'] . '</a></li>';
			}
			else
				echo '<li><a href="' . Yii::app()->getBaseUrl(true) . '/' . $tag['nome'] . '">' . $tag['nome'] . '</a></li>';
			
			
		}
		echo '</ul>';
		
		foreach($tags as $tag)
			self::_setHeaderSubmenu($tag, $tagsSelecionadas, $tagsFilhasDe, '/' . $tag['nome']);
	
		
		$cssComponents = '';
		foreach(self::$_niveis as $n) {
			
			$opacity = 100 - ($n * 10); 
			$minorOpacity = $opacity/100;
			
			$cssComponents .= ' .nivel' . $n . ' { 
				-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=' . $opacity . ')";
				filter: alpha(opacity=' . $opacity . ');
				-moz-opacity: ' . $minorOpacity . ';
				-khtml-opacity: ' . $minorOpacity . ';
				opacity: ' . $minorOpacity . ';
			}';
		}
		
		Yii::app()->clientScript->registerCss('niveis', $cssComponents);
	}
	
	private static function _setHeaderSubmenu($tag, $tagsSelecionadas, $tagsFilhasDe, $hashTags = null, $nivel = 2, $categoriaPrincipal = null) {
		
		$principal = $categoriaPrincipal ? $categoriaPrincipal : $tag['nome'];
		$isItemActive = (strstr(('_' . implode('_',$tagsSelecionadas)), str_replace('/', '_',$hashTags)));		
		
		echo '<ul nivel="' . $nivel . '" principal="' . $principal . '" id_menu="sm' . str_replace('/', '_',$hashTags) . '" class="' . ($isItemActive ? 'show' : 'hide') . ' submenu nivel' . $nivel .'">';

			foreach($tag['filhos'] as $tagFilho) 
				echo '<li class="' . (isset($tagsSelecionadas[$nivel - 1]) ? ($tagFilho['nome'] == $tagsSelecionadas[$nivel - 1] ? 'active' : '') : '') . '"><a href="' . Yii::app()->getBaseUrl(true) . $hashTags .  '/' . $tagFilho['nome'] . '">' . $tagFilho['nome'] . '</a></li>';

		echo '</ul>';
		
		if(!in_array($nivel, self::$_niveis))
				self::$_niveis[] = $nivel;
		
		$nivel++;
		
		foreach($tag['filhos'] as $tagFilho) {
			
			if(isset($tagFilho['filhos']) && count($tagFilho['filhos'])) {
				
				$childrenTag = $hashTags . '/' . $tagFilho['nome'];
				self::_setHeaderSubmenu($tagFilho, $tagsSelecionadas, $tagsFilhasDe, $childrenTag, $nivel, $principal);
			}
		}
	}
	
	public static function footerMenu($tags) {
		echo '<ul>';
		
		foreach($tags as $tag) {
			
			echo '<li class="left">';
			
				echo '<a href="#">' .  $tag['nome'] . '</a>';
				
				if(count($tag['filhos'])) {
					
					echo '<ul>';
					foreach($tag['filhos'] as $tagFilho)
						echo '<li><a href="' . Yii::app()->getBaseUrl(true) . '/' . $tag['nome'] . '/' . $tagFilho['nome'] . '">' . $tagFilho['nome'] . '</a></li>';
					echo '</ul>';
				}
				
			echo '</li>';
		}
		
		echo '</ul>';
	}
}