[?php

/**
 * <?= $this->table->getOption('name'); ?> form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class <?= $this->table->getOption('name'); ?>Form extends Base<?= $this->table->getOption('name'); ?>Form
{
<?php
if ($parent = $this->getParentModel()) {
?>
    /**
     * @see <?= $parent; ?>Form
     */
    public function configure()
    {
        parent::configure();
    }
<?php
} else {
?>
    public function configure()
    {
    }
<?php
}
?>
}
