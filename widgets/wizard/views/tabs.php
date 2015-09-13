<div class="row">
    <div class="col-md-9 col-md-offset-3 col-xs-offset-3 col-xs-9">
        <div id="fuelux-wizard" class="wizard row text-center">
            <ul class="wizard-steps">

                <?php foreach ($tabs as $index => $tab) : ?>

                    <li data-target="#step<?= $index + 1; ?>" class="<?php echo $index === $active ? "active" : ""; ?>">
                        <?php if(isset($tabs[$active]['move']) && isset($tab['url']) && $tabs[$active]['move'] === true) : ?>
                            <a href="<?= is_array($params) ? Yii::app()->createAbsoluteUrl($tab['url'], $params) : Yii::app()->createAbsoluteUrl($tab['url']); ?>">
                        <?php endif; ?>

                        <span class="step"><?= $index + 1;?></span>
                        <span class="title"><?= $tab['desc']; ?></span>

                        <?php if(isset($tabs[$active]['move']) && isset($tab['url']) && $tabs[$active]['move'] === true) : ?>
                            </a>
                        <?php endif; ?>
                    </li>

                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
