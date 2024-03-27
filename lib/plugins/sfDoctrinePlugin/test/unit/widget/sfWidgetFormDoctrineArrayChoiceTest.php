<?php

$app = 'frontend';
$fixtures = 'fixtures/fixtures.yml';

include __DIR__ . '/../../bootstrap/functional.php';

$t = new lime_test(2);

// ->getChoices()
$t->diag('->getChoices()');

$validator = new sfWidgetFormDoctrineArrayChoice(['model' => 'Author', 'table_method' => 'getChoices']);

$t->is_deeply($validator->getChoices(), [1 => 'Jonathan H. Wage', 2 => 'Fabien POTENCIER'], '->getChoices() returns choices');

$validator->setOption('table_method_params', [1]);

$t->is_deeply($validator->getChoices(), [1 => 'Jonathan H. Wage'], '->getChoices() returns limited choices');
