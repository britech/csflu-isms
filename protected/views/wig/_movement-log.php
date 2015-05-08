<?php

namespace org\csflu\isms\views;
?>
<script type="text/javascript" src="protected/js/wig/_movement-log.js"></script>
<table class="ink-table bordered" style="margin-bottom: 10px;">
    <tbody>
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: center;">Alignment Data</td>
        </tr>
        <tr>
            <td style="font-weight: bold; width: 20%;">Unit Breakthrough</td>
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