[?php

/**
 * <?= $this->table->getOption('name'); ?> filter form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
class <?= $this->table->getOption('name'); ?>FormFilter extends Plugin<?= $this->table->getOption('name'); ?>FormFilter
{
<?php
if ($parent = $this->getParentModel()) {
?>
    /**
     * @see <?= $parent; ?>FormFilter
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
