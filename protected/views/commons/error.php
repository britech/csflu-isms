<?php $exception = $params['exception']; ?>
<div class="ink-alert block error">
    <h4>ISMS Error - <?php echo $exception->getMessage(); ?></h4>
    <p>
        An error occured during execution of the application. Below are the details of the exception for your reference and debugging.
        <small style="display: block; margin-top: 20px;">
            <span style="display:block;">
                <span style="display: block; font-weight: bold;">Caused by:&nbsp;</span> 
                <?php echo $exception->getMessage() . ' on ' . $exception->getFile() . ' at line ' . $exception->getLine(); ?>
            </span>

            <span style="display: block; margin-top: 20px; font-weight: bold;">Stacktrace:&nbsp;</span>
            <?php
            $exceptionList = explode("\n", $exception->getTraceAsString());

            for ($i = 0; $i < (count($exceptionList) - 1); $i++):
                ?>
                <span style="display: block;"><?php echo $exceptionList[$i] ?></span>
            <?php endfor; ?>
        </small>
    </p>
</div>
