<?php
namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
$confirmData = $params['confirm'];

if(empty($confirmData) || !is_array($confirmData)){
    $this->viewWarningPage('Confirmation Dialog Setup is invalid', 'Please provide the proper definition to construct the confirmation dialog properly.');
}
?>
<div class="ink-alert block <?php echo $confirmData['class']?>" style="margin-top: 0px;">
    <h4><?php echo $confirmData['header']?></h4>
    <p>
        <?php echo $confirmData['text']?>
        <span style="display: block; text-align: center; margin-top: 10px;">
        <?php echo ApplicationUtils::generateLink($confirmData['accept.url'], $confirmData['accept.text'], array('class'=>'ink-button '.$confirmData['accept.class'].' flat'))?>
        &nbsp;
        <?php echo ApplicationUtils::generateLink($confirmData['deny.url'], $confirmData['deny.text'], array('class'=>'ink-button '.$confirmData['deny.class'].' flat'))?>
        </span>
    </p>
</div>