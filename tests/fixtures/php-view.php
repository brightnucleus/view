<?php
/*
 * PHP Test View.
 */
?><html>
    <body>
        <h1><?php echo $this->title; ?></h1>
<?php foreach ([1, 2, 3] as $this->paragraph) { ?>
        <p>Paragraph <?php echo $this->paragraph; ?></p>
<?php } ?>
    </body>
</html>
