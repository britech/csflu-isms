<?php

namespace org\csflu\isms\views;

ob_end_clean();
error_reporting(0);

$headerHtml = <<<HEADER
<thead>
    <tr>
        <th style="border: 1px solid #000000; background-color: black; color: white;" colspan="9">FIRST LEVEL STRATEGIC INITIATIVES</th>
    </tr>
    <tr>
        <th style="border: 1px solid #000000;" colspan="4">PROGRESS FOR THE MONTH OF:</th>
        <th style="border: 1px solid #000000;" colspan="5">{$period->format('F Y')}</th>
    </tr>
    <tr>
        <th style="border: 1px solid #000000;" colspan="4">INITIATIVE NAME</th>
        <th style="border: 1px solid #000000;" colspan="3">DESCRIPTION/INNOVATIVE COMPONENT</th>
        <th style="border: 1px solid #000000;" colspan="2">INITIATIVE TEAM</th>
    </tr>
    <tr>
        <td style="border: 1px solid #000000;" colspan="4">{$initiative->title}</td>
        <td style="border: 1px solid #000000;" colspan="3">{$initiative->description}</td>
        <td style="border: 1px solid #000000;" colspan="2">INITIATIVE TEAM</td>
    </tr>
        
    <tr>
        <th style="border: 1px solid #000000; background-color: black; color: white;" colspan="9">PROJECT MANAGEMENT COMPONENT</th>
    </tr>
    <tr>
        <th style="border: 1px solid #000000;" colspan="1">STATUS</th>
        <th style="border: 1px solid #000000;" colspan="2">APPROVED CONCEPT</th>
        <th style="border: 1px solid #000000;" colspan="1">ADOPTED IN AIP</th>
        <th style="border: 1px solid #000000;" colspan="2">ORGANIZED PROJECT TEAM</th>
        <th style="border: 1px solid #000000;" colspan="1">APPROVED BUDGET</th>
        <th style="border: 1px solid #000000;" colspan="2">REPORTING MECHANISM</th>
    </tr>
    <tr>
        <td style="border: 1px solid #000000; text-align: center;" colspan="1">{$initiative->translateStatusCode()}</td>
        <td style="border: 1px solid #000000; text-align: center;" colspan="2">Y</td>
        <td style="border: 1px solid #000000; text-align: center;" colspan="1">Y</td>
        <td style="border: 1px solid #000000; text-align: center;" colspan="2">Y</td>
        <td style="border: 1px solid #000000; text-align: center;" colspan="1">Y</td>
        <td style="border: 1px solid #000000; text-align: center;" colspan="2">MONTHLY</td>
    </tr>
    
    <tr>
        <th style="border: 1px solid #000000; background-color: black; color: white;" colspan="9">PROJECT MILESTONES AND BUDGET</th>
    </tr>
</thead>
HEADER;


$html = <<<TABLE
<table class="ink-table bordered" style="font-family: sans-serif; color: black; font-size: 10px;">
    {$headerHtml}
</table>
TABLE;

$pdf = new \mPDF('c', 'A4');
$pdf->mirrorMargins = .5;
$pdf->AddPage('L');
$css = file_get_contents('assets/ink/css/ink.css');
$pdf->writeHTML($css, 1);
$pdf->writeHTML($html);

$pdf->Output("RPT_INITIATIVE_UPDATE_{$initiative->id}_{$period->format('F Y')}.pdf", 'D');
