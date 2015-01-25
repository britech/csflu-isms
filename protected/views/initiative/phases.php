<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('project/enlistPhase'),
    'class' => 'ink-form',
    'hasFieldset' => true
));
?>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent();?>
        <?php echo $form->constructHeader('Enlist a Phase');?>
        
        <?php echo $form->endComponent();?>
    </div>
    <div class="all-50">
        
    </div>
</div>