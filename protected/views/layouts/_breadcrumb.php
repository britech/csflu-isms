<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils as ApplicationUtils;

if (isset($breadcrumb) && !empty($breadcrumb)):
    ?>
    <div class="column-group quarter-gutters">
        <div class="all-100">
            <div class="ink-navigation">
                <ul class="breadcrumbs">
                    <span><i class="fa fa-location-arrow"></i>&nbsp;You are now at: &nbsp;</span>
                    <?php foreach ($breadcrumb as $label => $url): ?>
                            <?php if ($url == 'active'): ?>
                            <li class="active"><?php echo ApplicationUtils::generateLink('#', $label); ?></li>
                        <?php else: ?>
                            <li><?php echo ApplicationUtils::generateLink($url, $label); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif;