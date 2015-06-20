<!DOCTYPE html>
<html>
    <head>
        <link href="assets/ink/css/ink.css" rel="stylesheet" type="text/css"/>
        <link href="assets/ink/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/app.css" rel="stylesheet" type="text/css"/>
        <title><?php echo empty($this->title) ? org\csflu\isms\core\ApplicationConstants::APP_NAME : $this->title; ?></title>
    </head>
    <body>
        <div class="ink-grid" style="margin-top: 10px;">
            <a name="top"></a>
            <?php include_once '_breadcrumb.php';?>
            <div class="column-group quarter-gutters">
                <div class="all-20">
                    <?php include_once '_sidebar.php';?>
                </div>
                <div class="all-80">
                    <?php include_once $body;?>
                </div>
            </div>
        </div>
    </body>
</html>