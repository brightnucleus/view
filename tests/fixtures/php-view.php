<?php
/*
 * PHP Test View.
 */
?><html>
    <body>
        <h1><?php echo $title; ?></h1>
<?php foreach( [1, 2, 3] as $paragraph ) { ?>
        <p>Paragraph <?php echo $paragraph; ?></p>
<?php } ?>
    </body>
</html>
