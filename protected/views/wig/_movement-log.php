<?php

namespace org\csflu\isms\views;
?>
<script type="text/javascript" src="protected/js/wig/_movement-log.js"></script>
<table class="ink-table bordered">
    <tbody>
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: center;">Alignment Data</td>
        </tr>
        <tr>
            <td style="font-weight: bold; width: 15%;">Unit Breakthrough</td>
            <td><?php echo $ubt->description; ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Lead Measure 1</td>
            <td><?php echo $lm1->description; ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Lead Measure 2</td>
            <td><?php echo $lm2->description; ?></td>
        </tr>
    </tbody>
</table>
<div id="log-<?php echo $data->id; ?>" style="margin-bottom: 1em;"></div>
<button class="ink-button blue flat" id="refresh" style="margin-bottom: 1em;">Refresh</button>