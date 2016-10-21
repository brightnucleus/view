<?php
/*
 * PHP Test Partial Parent View.
 */
?><h1>Partial Parent View</h1>
<p>PARTIAL.PARENT-<?= $this->renderPart('partial.child1') ?>-<?= $this->renderPart('partial.child2') ?><p>
