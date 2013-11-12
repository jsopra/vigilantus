<!DOCTYPE HTML>
<?php Yii::app()->bootstrap->register(); ?>
<html lang="<?php echo Yii::app()->getLanguage(); ?>" xml:lang="<?php echo Yii::app()->getLanguage(); ?>">
    <head>
        <meta charset="<?php echo Yii::app()->charset; ?>" />
        <meta name="robots" content="noindex,nofollow">
		<title><?php echo isset($this->pageTitle) ? $this->pageTitle : Yii::app()->name; ?></title>
		<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->controller->assetPath . '/css/screen.css.php');?>
		<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->controller->assetPath . '/js/site.js');?>
		<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->controller->assetPath . '/js/common.js.php');?>
    </head>
    <body>
        <div class="" id="page">
			
			<header id="header">
				<?php
					$this->widget(
						'bootstrap.widgets.TbNavbar',
						array(
							'fixed'=>false,
							'brand'=> Yii::app()->name,
							'brandUrl'=> Yii::app()->request->baseUrl,
							'collapse'=>true,
							'items'=> Layout::getMenu()
						)
					); 
				?>
			</header>     
			
			<?php if(isset($this->breadcrumbs)):?>
                <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                    'links'=>$this->breadcrumbs,
					'homeLink'=> CHtml::link(Yii::t('zii','Início'),Yii::app()->createAbsoluteUrl('admin/default/index'))

                )); ?>
			<?php endif; ?>
			
            <?php $this->widget('bootstrap.widgets.TbAlert'); ?>
                
            <section>
                <?php echo $content; ?>
            </section>
			
			<div class="clear"></div>

			<footer id="footer"><?php echo Yii::app()->name; ?> &copy; <?php echo date('Y'); ?></footer>
    </body>
</html>