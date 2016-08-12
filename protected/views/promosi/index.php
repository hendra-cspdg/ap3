<?php

/* @var $this PromosiController */
/* @var $model Promosi */

$this->breadcrumbs = array(
    'Promosi' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Promosi';
$this->boxHeader['normal'] = 'Promosi';

$this->widget('BGridView', array(
    'id' => 'promosi-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'nama',
        'dari',
        'sampai',
        'nominal',
        'persen',
        /*
          'khusus_member',
          'prioritas',
          'semua_barang',
          'status',
          'updated_at',
          'updated_by',
          'created_at',
         */
        array(
            'class' => 'BButtonColumn',
        ),
    ),
));

$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 't'
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
