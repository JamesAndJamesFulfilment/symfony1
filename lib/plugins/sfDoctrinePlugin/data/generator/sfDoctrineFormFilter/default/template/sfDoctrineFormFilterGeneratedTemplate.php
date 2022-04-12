[?php

/**
 * <?= $this->table->getOption('name'); ?> filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
abstract class Base<?= $this->table->getOption('name'); ?>FormFilter extends <?= $this->getFormClassToExtend() . PHP_EOL; ?>
{
    public function setup()
    {
        $this->setWidgets([
<?php
$columns   = $this->getColumns();
$relations = $this->getManyToManyRelations();

foreach ($columns as $column) {
    if ($column->isPrimaryKey()) {
        continue;
    }

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
                'model'    => '<?= $relation['table']->getOption('name') ?>',
            ]),
<?php
}
?>
        ]);

        $this->setValidators([
<?php
foreach ($columns as $column) {
    if ($column->isPrimaryKey()) {
        continue;
    }

    $field_name = $column->getFieldName();
    $padding    = str_repeat(' ', $this->getColumnNameMaxLength() - strlen($field_name));
?>
            '<?= $field_name; ?>'<?= $padding; ?> => <?= $this->getValidatorForColumn($column); ?>,
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

        $this->widgetSchema->setNameFormat('<?= $this->underscore($this->modelName); ?>_filters[%s]');

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

        $this->setupInheritance();

        parent::setup();
    }

<?php
foreach ($relations as $relation) {
    $name = $relation['refTable']->getOption('name');
?>
    public function add<?= sfInflector::camelize($relation['alias']); ?>ListColumnQuery(Doctrine_Query $query, $field, $values)
    {
        if (!is_array($values)) {
            $values = array($values);
        }

        if (!count($values)) {
            return;
        }

        $query
            ->leftJoin($query->getRootAlias() . '.<?= $name ?> <?= $name; ?>')
            ->andWhereIn('<?= $name; ?>.<?= $relation->getForeignFieldName(); ?>', $values);
    }

<?php
}
?>
    public function getModelName()
    {
        return '<?= $this->modelName; ?>';
    }

    public function getFields()
    {
        return [
<?php
foreach ($columns as $column) {
    $field_name = $column->getFieldName();
    $padding    = str_repeat(' ', $this->getColumnNameMaxLength() - strlen($field_name));
?>
            '<?= $field_name ?>'<?= $padding; ?> => '<?= $this->getType($column); ?>',
<?php
}
?>
<?php
foreach ($relations as $relation) {
    $alias = $this->underscore($relation['alias']);
    $padding = str_repeat(' ', $this->getColumnNameMaxLength() - strlen("{$alias}_list"));
?>
            '<?= $alias; ?>_list'<?= $padding; ?> => 'ManyKey',
<?php
}
?>
        ];
    }
}
