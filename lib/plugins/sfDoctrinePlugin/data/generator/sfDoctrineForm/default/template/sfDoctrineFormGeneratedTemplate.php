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
abstract class Base<?= $this->modelName; ?>Form extends <?= $this->getFormClassToExtend() . PHP_EOL ?>
{
    public function setup()
    {
        $this->setWidgets([
<?php
$columns   = $this->getColumns();
$relations = $this->getManyToManyRelations();
foreach ($columns as $column) {
    $field_name = $column->getFieldName();
    $padding    = str_repeat(' ', $this->getColumnNameMaxLength() - strlen($field_name));
    $class      = $this->getWidgetClassForColumn($column);
    $arguments  = $this->getWidgetOptionsForColumn($column);
?>
            '<?= $field_name; ?>'<?= $padding; ?> => new <?= $class; ?>(<?= $arguments; ?>),
<?php
}
?>
<?php
foreach ($relations as $relation) {
    $alias   = $this->underscore($relation['alias']);
    $padding = str_repeat(' ', $this->getColumnNameMaxLength() - strlen("{$alias}_list"));
?>
            '<?= $alias; ?>_list'<?= $padding; ?> => new sfWidgetFormDoctrineChoice([
                'multiple' => true,
                'model'    => '<?= $relation['table']->getOption('name'); ?>',
            ]),
<?php
}
?>
        ]);

        $this->setValidators([
<?php
foreach ($columns as $column) {
    $field_name = $column->getFieldName();
    $padding    = str_repeat(' ', $this->getColumnNameMaxLength() - strlen($field_name));
    $class      = $this->getValidatorClassForColumn($column);
    $arguments  = $this->getValidatorOptionsForColumn($column);
?>
            '<?= $field_name; ?>'<?= $padding; ?> => new <?= $class; ?>(<?= $arguments; ?>),
<?php
}
?>
<?php
foreach ($relations as $relation) {
    $alias   = $this->underscore($relation['alias']);
    $padding = str_repeat(' ', $this->getColumnNameMaxLength() - strlen("{$alias}_list"));
?>
            '<?= $alias; ?>_list'<?= $padding; ?> => new sfValidatorDoctrineChoice([
                'multiple' => true,
                'model'    => '<?= $relation['table']->getOption('name'); ?>',
                'required' => false,
            ]),
<?php
}
?>
        ]);

<?php
if ($uniqueColumns = $this->getUniqueColumnNames()) {
    $table_name = $this->table->getOption('name');
?>
        $this->validatorSchema->setPostValidator(
<?php
    if (count($uniqueColumns) > 1) {
?>
            new sfValidatorAnd([
<?php
        foreach ($uniqueColumns as $uniqueColumn) {
?>
                new sfValidatorDoctrineUnique([
                    'model'  => '<?= $table_name; ?>',
                    'column' => ['<?= implode("', '", $uniqueColumn); ?>'],
                ]),
<?php
        }
?>
            ])
<?php
    } else {
?>
            new sfValidatorDoctrineUnique([
                'model'  => '<?= $table_name; ?>',
                'column' => ['<?= implode("', '", $uniqueColumns[0]); ?>'],
            ])
<?php
    }
?>
        );
<?php
}
?>
        $this->widgetSchema->setNameFormat('<?= $this->underscore($this->modelName); ?>[%s]');

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

        $this->setupInheritance();

        parent::setup();
    }

    public function getModelName()
    {
        return '<?= $this->modelName; ?>';
    }

<?php
if ($relations) { ?>
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
            $this->object->link('<?= $relation['alias']; ?>', array_values($link));
        }
    }

<?php
    }
?>
<?php
}
?>
}
