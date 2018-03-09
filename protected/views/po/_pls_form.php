<div class="row">
    <div class="small-12 medium-4 large-2 columns">
        <?php
                    echo CHtml::activeLabelEx($modelReportPls, 'jumlahHari');
                    echo CHtml::activeTextField($modelReportPls, 'jumlahHari', ['value' => empty($modelReportPls->jumlahHari) ? '30' : $model->jumlahHari]);
                    // echo $form->error($model, 'jumlahHari', ['class' => 'error']);
        ?>
    </div>
    <div class="small-12 medium-4 large-2 columns end">
        <?php
                    echo CHtml::activeLabelEx($modelReportPls, 'sisaHariMax');
                    echo CHtml::activeTextField($modelReportPls, 'sisaHariMax', ['value' => empty($modelReportPls->sisaHariMax) ? '7' : $model->sisaHariMax]);
                    // echo $form->error($model, 'sisaHariMax', ['class' => 'error']);
        ?>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <?=
        CHtml::link('<i class="fa fa-calculator fa-fw"></i> <span class="ak">A</span>nalisa Data PLS', '#', [
            'class'     => 'tiny bigfont button',
            'accesskey' => 'a',
            'id'        => 'tombol-analisa-pls'
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <div class="error" style="display: none"></div>
    </div>
</div>

<script>
var handler = function(e) {
    $("#tombol-analisa-pls").off();
    dataUrl = "<?php echo $this->createUrl('ambilpls', ['id' => $model->id]); ?>";
    dataKirim = {
        'ambil': true,
        'hariPenjualan': $("#ReportPlsForm_jumlahHari").val(),
        'hariSisa': $("#ReportPlsForm_sisaHariMax").val(),
    };
    $.ajax({
        type: 'POST',
        url: dataUrl,
        data: dataKirim,
        beforeSend: function() {
            $("#tombol-analisa-pls").addClass("tombol-loading");
            $("#tombol-analisa-pls").html("<i class=\"fa fa-refresh fa-spin fa-fw\"></i> <span class=\"ak\">A</span>mbil Data PLS");
        },
        success: function(data) {
            if (data.sukses) {
                $.fn.yiiGridView.update('pls-detail-grid')
            } else {
                $(".error").html("Error " + data.error.code + ": " + data.error.msg).slideDown(500);
            }
        },
        complete: function() {
            $("#tombol-analisa-pls").removeClass("tombol-loading");
            $("#tombol-analisa-pls").html("<i class=\"fa fa-calculator fa-fw\"></i> <span class=\"ak\">A</span>nalisa Data PLS");
            $("#tombol-analisa-pls").on("click", handler);
        }
    });
    return false;
}

$('#tombol-analisa-pls').on('click', handler);

function ambilTotal() {
    $("#total-po").load("<?= $this->createAbsoluteUrl('po/ambiltotal', ['id'=>$model->id]) ?>");
    // console.log("<?= $this->createUrl('ambiltotal', ['id'=>$model->id]) ?>");
}

</script>
