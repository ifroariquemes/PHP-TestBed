<?php include './vendor/autoload.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>PHP-TestBed</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="app/css/style.css">
    </head>
    <body>
        <?php PhpTestBed\ScriptCrawler::getInstance('test.php')->run(); ?>
    </body>
</html>