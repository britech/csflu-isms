<?php if (isset($message) && is_array($message)): ?>
    <div class="ink-alert block warning" style="margin-top: 0px;">
        <h4>Validation error. Please check your entries</h4>
        <p><?php echo implode('<br/>', $message); ?></p>
    </div>
<?php
endif;
