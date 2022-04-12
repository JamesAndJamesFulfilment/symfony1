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
    protected function setupInheritance()
    {
        parent::setupInheritance();

<?php
$columns   = $this->getColumns();
$relations = $this->getManyToManyRelations();
foreach ($columns as $column) {
    $field_name = $column->getFieldName();
    $class      = $this->getWidgetClassForColumn($column);
    $arguments  = $this->getWidgetOptionsForColumn($column);
?>
        $this->widgetSchema['<?= $field_name ?>']    = new <?= $class; ?>(<?= $arguments; ?>);
        $this->validatorSchema['<?= $field_name ?>'] = <?= $this->getValidatorForColumn($column); ?>;

<?php
}
?>
<?php
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
        $this->widgetSchema->setNameFormat('<?= $this->underscore($this->modelName); ?>_filters[%s]');
    }

<?php
foreach ($relations as $relation) {
    $name = $relation['refTable']->getOption('name');
?>
    public function add<?= sfInflector::camelize($relation['alias']); ?>ListColumnQuery(Doctrine_Query $query, $field, $values)
    {
        if (!is_array($values)) {
            $values = [$values];
        }

        if (!count($values)) {
            return;
        }

        $query
            ->leftJoin($query->getRootAlias() . '.<?= $name; ?> <?= $name; ?>')
            ->andWhereIn('<?= $name; ?>.<?= $relation->getForeignFieldName() ?>', $values);
    }

<?php
}
?>
    public function getModelName()
    {
        return '<?= $this->modelName; ?>';
    }
<?php
if (count($columns) || count($relations)) {
?>

    public function getFields()
    {
        return array_merge(parent::getFields(), [
<?php
    foreach ($columns as $column) {
?>
            '<?= $column->getFieldName(); ?>' => '<?= $this->getType($column); ?>',
<?php
    }
?>
<?php
    foreach ($relations as $relation) {
?>
            '<?= $this->underscore($relation['alias']); ?>_list' => 'ManyKey',
<?php
    }
?>
        ]);
    }
<?php
}
?>
}
