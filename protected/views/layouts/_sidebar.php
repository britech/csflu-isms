<?php 
namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils as ApplicationUtils;

if(isset($params['sidebar']['data']) && !empty($params['sidebar']['data'])): 
    $sidebar = $params['sidebar']['data'];
?>
<div class="ink-navigation">
    <ul class="menu vertical">
        <li class="heading"><?php echo ApplicationUtils::generateLink('#', $sidebar['header'], array('style'=>'padding-left:0px;'))?></li>
        <?php foreach($sidebar['links'] as $label => $url):?>
        <li><?php echo ApplicationUtils::generateLink($url, $label);?></li>
        <?php endforeach;?>
    </ul>
</div>
<?php elseif(isset($params['sidebar']['file']) && !empty($params['sidebar']['file'])):
    $this->renderPartial($params['sidebar']['file'], $params);
?>
<?php else:
    $this->viewWarningPage('Sidebar needed', 'This layout needs a sidebar to render the page properly.');
endif;