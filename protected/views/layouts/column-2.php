<!DOCTYPE html>
<html>
    <head>
        <link href="assets/ink/css/ink.css" rel="stylesheet" type="text/css"/>
        <link href="assets/ink/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/jqwidgets/styles/jqx.base.css" rel="stylesheet" type="text/css"/>
        <link href="assets/jqwidgets/styles/jqx.office.css" rel="stylesheet" type="text/css"/>
        <link href="assets/app.css" rel="stylesheet" type="text/css"/>
        
        <script src="assets/jquery/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="assets/jqwidgets/jqx-all.js" type="text/javascript"></script>
        <title><?php echo empty($this->title) ? org\csflu\isms\core\ApplicationConstants::APP_NAME : $this->title; ?></title>
    </head>
    <body style="background-color: #eee;">
        <?php include_once '_navigation.php' ?>
        <div class="ink-grid" style="margin-top: 50px;">
            <?php include_once '_breadcrumb.php';?>
            <div class="column-group quarter-gutters">
                <div class="all-20">
                    <?php include_once '_sidebar.php';?>
                </div>
                <div class="all-80">
                    <?php include_once $body;?>
                </div>
            </div>
            <?php include_once '_footer.php'; ?>
        </div>
    </body>
</html>