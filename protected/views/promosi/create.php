<?php
/* @var $this PromosiController */
/* @var $model Promosi */

$this->breadcrumbs=array(
	'Promosis'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Promosi', 'url'=>array('index')),
	array('label'=>'Manage Promosi', 'url'=>array('admin')),
);
?>

<h1>Create Promosi</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>