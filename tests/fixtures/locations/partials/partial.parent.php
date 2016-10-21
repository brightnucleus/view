<?php
/*
 * PHP Test Partial Parent View.
 */
?><h1>Partial Parent View</h1>
<p>PARTIAL.PARENT-<?php echo $this->renderPart( 'partial.child1', $this->context ); ?>-<?php echo $this->renderPart( 'partial.child2', $this->context ); ?><p>
