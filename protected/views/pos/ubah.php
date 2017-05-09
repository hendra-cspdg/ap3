<?php
/* @var $this PosController */
/* @var $model Penjualan */

$this->breadcrumbs = array(
    'Penjualan' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Ubah',
);

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = "Penjualan: {$model->nomor}";
?>

<div class="medium-7 large-7 columns" style="/*height: 100%; overflow: scroll*/">
    <div class="row collapse">
        <div class="small-2 medium-1 columns">
            <span class="prefix" id="scan-icon"><i class="fa fa-barcode fa-2x"></i></span>
        </div>
        <div class="small-6 medium-9 columns">
            <input id="scan" type="text"  placeholder="Scan [B]arcode / Input nama" accesskey="b" autofocus="autofocus"/>
        </div>
        <div class="small-2 medium-1 columns">
            <a href="#" class="button postfix" id="tombol-tambah-barang"><i class="fa fa-level-down fa-2x fa-rotate-90"></i></a>
        </div>
        <?php
        switch ($tipeCari):
            case Pos::CARI_AUTOCOMPLETE:
                ?>
                <div class="small-2 medium-1 columns">
                    <a href="#" class="success button postfix" id="tombol-cari-barang" accesskey="c"><i class="fa fa-search fa-2x"></i></a>
                </div>
                <?php
                break;

            case Pos::CARI_TABLE:
                ?>
                <div class="small-2 medium-1 columns">
                    <a href="#" class="success button postfix" id="tombol-cari-tabel" accesskey="c"><i class="fa fa-search-plus fa-2x"></i></a>
                </div>
                <?php
                break;
        endswitch;
        ?>
    </div>
    <div id="transaksi">
        <?php
        $this->renderPartial('_detail', array(
            'penjualan' => $model,
            'penjualanDetail' => $penjualanDetail
        ));
        ?>
    </div>
    <div id="barang-list" style="display:none">
        <?php
        $this->renderPartial('_barang_list', array(
            'barang' => $barang,
        ));
        ?>
    </div>
</div>
<div class="medium-3 large-3 columns sidebar kanan">
    <div id="total-belanja">
        <?php echo $model->getTotal(); ?>
    </div>
    <div id="kembali">
        0
    </div>
    <div class="row collapse">
        <?php /* Company account */ ?>
        <div class="small-3 large-2 columns">
            <span class="prefix"><i class="fa fa-2x fa-chevron-right"></i></span>
        </div>
        <div class="small-6 large-7 columns">
  <!--         <select accesskey="a">
              <option value="1">Cash</option>
           </select>-->
            <?php
            echo CHtml::dropDownList('account', 1, CHtml::listData(KasBank::model()->findAll(), 'id', 'nama'), array(
                'accesskey' => 'a',
                'id' => 'account'
            ));
            ?>
        </div>
        <div class="small-3 large-3 columns">
            <span class="postfix">[A]</span>
        </div>
    </div>
    <div class="row collapse">
        <?php /* Jenis Pembayaran */ ?>
        <div class="small-3 large-2 columns">
            <span class="prefix"><i class="fa fa-2x fa-chevron-right"></i></span>
        </div>
        <div class="small-6 large-7 columns">
            <?php
            echo CHtml::dropDownList('jenisbayar', 1, CHtml::listData(JenisTransaksi::model()->findAll(), 'id', 'nama'), array(
                'accesskey' => 'd',
                'id' => 'jenisbayar'
            ));
            ?>
        </div>
        <div class="small-3 large-3 columns">
            <span class="postfix">[D]</span>
        </div>
    </div>
    <div class="row collapse">
        <div class="small-3 large-2 columns">
            <span class="prefix huruf">IDR</span>
        </div>
        <div class="small-9 large-10 columns">
            <input type="text" id="uang-dibayar" placeholder="[U]ang Dibayar" accesskey="u"/>
        </div>
    </div>
    <a href="" class="success bigfont tiny button" id="tombol-simpan">Simpan</a>
    <a href="" class="warning bigfont tiny button" id="tombol-batal">Batal</a>
</div>
<div style="display: none" id="total-belanja-h"><?php echo $model->ambilTotal(); ?></div>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<script>
    function tampilkanKembalian() {
        //console.log("this:" + $(this).val() + "; total:" + $("#total-belanja-h").text());
        var dataKirim = {
            total: $("#total-belanja-h").text(),
            bayar: $("#uang-dibayar").val()
        };
        $("#kembali").load('<?php echo $this->createUrl('kembalian'); ?>', dataKirim);
    }

    function kirimBarcode() {
        dataUrl = '<?php echo $this->createUrl('tambahbarang', array('id' => $model->id)); ?>';
        dataKirim = {barcode: $("#scan").val()};
        console.log(dataUrl);
        /* Jika tidak ada barang, keluar! */
        if ($("#scan").val() === '') {
            $("#barang-list:visible").hide(100, function () {
                $("#transaksi").show(100);
            });
            $("#scan").focus();
            return false;
        }

        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function (data) {
                if (data.sukses) {
                    $("#tombol-admin-mode").removeClass('geleng');
                    $("#tombol-admin-mode").removeClass('alert');
                    $.fn.yiiGridView.update('penjualan-detail-grid');
                    updateTotal();
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 3000,
                        //class_name: 'gritter-center'
                    });
                }
                $("#scan").val("");
                $("#scan").focus();
                $("#scan").autocomplete("disable");
            }
        });
    }

    $(function () {
        $("#scan").autocomplete("disable");
        $(document).on('click', "#tombol-tambah-barang", function () {
            kirimBarcode();
            return false;
        });
        $(document).on('click', "#tombol-cari-barang", function () {
            $("#scan").autocomplete("enable");
            var nilai = $("#scan").val();
            $("#scan").autocomplete("search", nilai);
            $("#scan").focus();
        });
        $(document).on('click', "#tombol-cari-tabel", function () {
            var datakirim = {
                'cariBarang': true,
                'namaBarang': $("#scan").val(),
                'Barang_page': 1
            };
            $('#barang-grid').yiiGridView('update', {
                data: datakirim
            });
            $("#transaksi").hide(0, function () {
                $("#barang-list").show(100, function () {
                    $("#scan").val("");
                    $("#tombol-cari-tabel").focus();
                });

            });
            return false;
        });
    });

    $("#scan").keydown(function (e) {
        if (e.keyCode === 13) {
            $("#tombol-tambah-barang").click();
        }
    });

    $("#scan").autocomplete({
        source: "<?php echo $this->createUrl('caribarang'); ?>",
        minLength: 3,
        delay: 1000,
        search: function (event, ui) {
            $("#scan-icon").html('<img src="<?php echo Yii::app()->theme->baseUrl; ?>/css/3.gif" />');
        },
        response: function (event, ui) {
            $("#scan-icon").html('<i class="fa fa-barcode fa-2x"></i>');
        },
        select: function (event, ui) {
            console.log(ui.item ?
                    "Nama: " + ui.item.label + "; Barcode " + ui.item.value :
                    "Nothing selected, input was " + this.value);
            if (ui.item) {
                $("#scan").val(ui.item.value);
            }
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li style='clear:both'>")
                .append("<a><span class='ac-nama'>" + item.label + "</span> <span class='ac-harga'>" + item.harga + "</span> <span class='ac-barcode'><i>" + item.value + "</i></span> <span class='ac-stok'>" + item.stok + "</stok></a>")
                .appendTo(ul);
    };

    function updateTotal() {
        var dataurl = "<?php echo Yii::app()->createUrl('penjualan/total', array('id' => $model->id)); ?>";
        $.ajax({
            url: dataurl,
            type: "GET",
            success: function (data) {
                if (data.sukses) {
                    $("#total-belanja-h").text(data.total);
                    $("#total-belanja").text(data.totalF);
                    tampilkanKembalian();
                    console.log(data.totalF);
                }
            }
        });
    }

    $("#uang-dibayar").keyup(function () {
        tampilkanKembalian();
    });

    $("#uang-dibayar").keydown(function (e) {
        if (e.keyCode === 13) {
            $("#tombol-simpan").click();
        }
    });

    $("#tombol-simpan").click(function () {
        $(this).unbind("click").html("Simpan..").attr("class", "alert bigfont tiny button");

        dataUrl = '<?php echo $this->createUrl('simpan', array('id' => $model->id)); ?>';
        dataKirim = {
            'pos[account]': $("#account").val(),
            'pos[jenistr]': $("#jenisbayar").val(),
            'pos[uang]': $("#uang-dibayar").val()
        };
        console.log(dataUrl);
        printWindow = window.open('about:blank', '', 'left=20,top=20,width=400,height=600,toolbar=0,resizable=1');
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function (data) {
                if (data.sukses) {
                    //cetak();
                    printWindow.location.replace('<?php echo $this->createUrl('out', array('id' => $model->id)); ?>');
                    window.location.href = "<?php echo $this->createUrl('index'); ?>";
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 3000,
                    });
                }
                $("#scan").val("");
                $("#scan").focus();
            }
        });
        return false;
    });

    $("#tombol-batal").click(function () {
        dataUrl = '<?php echo $this->createUrl('hapus', array('id' => $model->id)); ?>';
        $.ajax({
            type: 'POST',
            url: dataUrl,
            success: function (data) {
                if (data.sukses) {
                    window.location.href = "<?php echo $this->createUrl('index'); ?>";
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 3000,
                    });
                    $("#tombol-admin-mode").addClass('geleng');
                    $("#tombol-admin-mode").addClass('alert');
                }
                $("#scan").val("");
                $("#scan").focus();
            }
        });
        return false;
    });
</script>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 't'
                )),
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                )),
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
