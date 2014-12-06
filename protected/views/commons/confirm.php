<?php
namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;


if(empty($confirm) || !is_array($confirm)):
    $this->viewWarningPage('Confirmation Dialog Setup is invalid', 'Please provide the proper definition to construct the confirmation dialog properly.');

else:
?>
<div class="ink-alert block <?php echo $confirm['class']?>" style="margin-top: 0px;">
    <h4><?php echo $confirm['header']?></h4>
    <p>
        <?php echo $confirm['text']?>
        <span style="display: block; text-align: center; margin-top: 10px;">
        <?php echo ApplicationUtils::generateLink($confirm['accept.url'], $confirm['accept.text'], array('class'=>'ink-button '.$confirm['accept.class'].' flat'))?>
        &nbsp;
        <?php echo ApplicationUtils::generateLink($confirm['deny.url'], $confirm['deny.text'], array('class'=>'ink-button '.$confirm['deny.class'].' flat'))?>
        </span>
    </p>
</div>
<?php endif;