<?php
/* @var $this PromosiController */
/* @var $data Promosi */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('nama')); ?>:</b>
    <?php echo CHtml::encode($data->nama); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('dari')); ?>:</b>
    <?php echo CHtml::encode($data->dari); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('sampai')); ?>:</b>
    <?php echo CHtml::encode($data->sampai); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('nominal')); ?>:</b>
    <?php echo CHtml::encode($data->nominal); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('persen')); ?>:</b>
    <?php echo CHtml::encode($data->persen); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('khusus_member')); ?>:</b>
    <?php echo CHtml::encode($data->khusus_member); ?>
    <br />

    <?php /*
      <b><?php echo CHtml::encode($data->getAttributeLabel('prioritas')); ?>:</b>
      <?php echo CHtml::encode($data->prioritas); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('semua_barang')); ?>:</b>
      <?php echo CHtml::encode($data->semua_barang); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
      <?php echo CHtml::encode($data->status); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('updated_at')); ?>:</b>
      <?php echo CHtml::encode($data->updated_at); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('updated_by')); ?>:</b>
      <?php echo CHtml::encode($data->updated_by); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
      <?php echo CHtml::encode($data->created_at); ?>
      <br />

     */ ?>

</div>