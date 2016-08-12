<?php
/* @var $this PromosiController */
/* @var $model Promosi */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'promosi-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'nama'); ?>
            <?php echo $form->textField($model, 'nama', array('size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'nama', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'dari'); ?>
            <?php echo $form->textField($model, 'dari'); ?>
            <?php echo $form->error($model, 'dari', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'sampai'); ?>
            <?php echo $form->textField($model, 'sampai'); ?>
            <?php echo $form->error($model, 'sampai', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'nominal'); ?>
            <?php echo $form->textField($model, 'nominal', array('size' => 18, 'maxlength' => 18)); ?>
            <?php echo $form->error($model, 'nominal', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'persen'); ?>
            <?php echo $form->textField($model, 'persen'); ?>
            <?php echo $form->error($model, 'persen', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'khusus_member'); ?>
            <?php echo $form->textField($model, 'khusus_member'); ?>
            <?php echo $form->error($model, 'khusus_member', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'prioritas'); ?>
            <?php echo $form->textField($model, 'prioritas'); ?>
            <?php echo $form->error($model, 'prioritas', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'semua_barang'); ?>
            <?php echo $form->textField($model, 'semua_barang'); ?>
            <?php echo $form->error($model, 'semua_barang', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'status'); ?>
            <?php echo $form->textField($model, 'status'); ?>
            <?php echo $form->error($model, 'status', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'updated_at'); ?>
            <?php echo $form->textField($model, 'updated_at'); ?>
            <?php echo $form->error($model, 'updated_at', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'updated_by'); ?>
            <?php echo $form->textField($model, 'updated_by', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'updated_by', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'created_at'); ?>
            <?php echo $form->textField($model, 'created_at'); ?>
            <?php echo $form->error($model, 'created_at', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('class' => 'tiny bigfont button')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>