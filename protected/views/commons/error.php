<?php $exception = $params['exception'];?>
<div class="ink-grid" style="margin-top: 20px;">
	<div class="ink-alert block error">
		<h4>ISMS Error - <?php echo $exception->getMessage();?></h4>
		<p>
			An error occured during execution of the application. Below is the stacktrace for your reference.
			<br/>
			<small>
			<?php
			$exceptionList = explode("\n", $exception->getTraceAsString());
			
			for ($i=0; $i < (count($exceptionList)-1); $i++){
				echo $exceptionList[$i].'<br>';
			}
			?>
			</small>
		</p>
	</div>
</div>
