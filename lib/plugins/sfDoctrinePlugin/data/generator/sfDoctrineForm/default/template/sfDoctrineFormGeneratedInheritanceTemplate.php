[?php

/**
 * <?= $this->modelName; ?> form base class.
 *
 * @method <?= $this->modelName; ?> getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
abstract class Base<?= $this->modelName; ?>Form extends <?= $this->getFormClassToExtend() . PHP_EOL; ?>
{
    protected function setupInheritance()
    {
        parent::setupInheritance();

<?php
foreach ($this->getColumns() as $column) {
    $field_name          = $column->getFieldName();
    $widget_class        = $this->getWidgetClassForColumn($column);
    $widget_arguments    = $this->getWidgetOptionsForColumn($column);
    $validator_class     = $this->getValidatorClassForColumn($column);
    $validator_arguments = $this->getValidatorOptionsForColumn($column);
?>
        $this->widgetSchema['<?= $field_name; ?>']    = new <?= $widget_class; ?>(<?= $widget_arguments; ?>);
        $this->validatorSchema['<?= $field_name; ?>'] = new <?= $validator_class; ?>(<?= $validator_arguments; ?>);

<?php
}
?>
<?php
$relations = $this->getManyToManyRelations();
foreach ($relations as $relation) {
    $alias      = $this->underscore($relation['alias']);
    $table_name = $relation['table']->getOption('name');
?>
        $this->widgetSchema['<?= $alias; ?>_list']    = new sfWidgetFormDoctrineChoice([
            'multiple' => true,
            'model'    => '<?= $table_name; ?>',
        ]);
        $this->validatorSchema['<?= $alias; ?>_list'] = new sfValidatorDoctrineChoice([
            'multiple' => true,
            'model'    => '<?= $table_name; ?>',
            'required' => false,
        ]);

<?php
}
?>
        $this->widgetSchema->setNameFormat('<?= $this->underscore($this->modelName); ?>[%s]');
    }

    public function getModelName()
    {
        return '<?= $this->modelName; ?>';
    }

<?php
if ($relations) {
?>
    public function updateDefaultsFromObject()
    {
        parent::updateDefaultsFromObject();

<?php
    foreach ($relations as $relation) {
        $alias = $this->underscore($relation['alias']);
?>
        if (isset($this->widgetSchema['<?= $alias; ?>_list'])) {
            $this->setDefault('<?= $alias; ?>_list', $this->object-><?= $relation['alias']; ?>->getPrimaryKeys());
        }

<?php
    }
?>
    }

    protected function doUpdateObject($values)
    {
<?php
    foreach ($relations as $relation) {
?>
        $this->update<?= $relation['alias']; ?>List($values);
<?php
    }
?>

        parent::doUpdateObject($values);
    }

<?php
    foreach ($relations as $relation) {
        $alias = $this->underscore($relation['alias']);
?>
    public function update<?= $relation['alias']; ?>List($values)
    {
        if (!isset($this->widgetSchema['<?= $alias; ?>_list'])) {
            // widget has been unset
            return;
        }

        if (!array_key_exists('<?= $alias; ?>_list', $values)) {
            // no values for this widget
            return;
        }

        $existing = $this->object-><?= $relation['alias']; ?>->getPrimaryKeys();
        $values = $values['<?= $alias; ?>_list'];
        if (!is_array($values)) {
            $values = [];
        }

        $unlink = array_diff($existing, $values);
        if (count($unlink)) {
            $this->object->unlink('<?= $relation['alias']; ?>', array_values($unlink));
        }

        $link = array_diff($values, $existing);
        if (count($link)) {
            $this->object->link('<?= $relation['alias'] ?>', array_values($link));
        }
    }

<?php
    }
}
?>
}
