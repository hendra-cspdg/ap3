<?php

/**
 * ReportDaftarBarangForm class.
 * ReportDaftarBarangForm is the data structure for keeping
 * report daftarbarang form data. It is used by the 'daftarbarang' action of 'ReportController'.
 * 
 */
class ReportDaftarBarangForm extends CFormModel
{

    const SORT_BY_BARCODE = 1;
    const SORT_BY_BARCODE_DSC = 2;
    const SORT_BY_NAMA = 3;
    const SORT_BY_NAMA_DSC = 4;
    const SORT_BY_KATEGORI = 5;
    const SORT_BY_KATEGORI_DSC = 6;

    public $kategoriId;
    public $profilId;
    public $hanyaDefault;
    public $rakId;
    public $sortBy0;
    public $sortBy1;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['profilId, sortBy0, sortBy1', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['kategoriId, profilId, hanyaDefault, rakId', 'safe']
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'kategoriId' => 'Kategori',
            'profilId' => 'Profil / Supplier',
            'hanyaDefault' => 'Hanya Default',
            'rakId' => 'Rak',
            'sortBy0' => 'Sort 1',
            'sortBy1' => 'Sort 2',
        ];
    }

    public function reportDaftarBarang()
    {
        $sqlSup = '';
        if (!empty($this->profilId)) {
            $sqlSupWhere = $this->hanyaDefault ? 'AND sup.`default` = 1' : '';
            $sqlSup = "
                JOIN
            supplier_barang sup ON sup.barang_id = barang.id AND sup.supplier_id= :supplierId {$sqlSupWhere}";
        }
        $sqlOrder = "
                ORDER BY {$this->listSortBy2()[$this->sortBy0]}, {$this->listSortBy2()[$this->sortBy1]}
                ";
        $sql = "
        SELECT 
            barang.barcode,
            barang.nama,
            pd.harga_beli hpp,
            bhj.harga harga_jual,
            bhjr.harga rrp,
            kat.nama nama_kategori
        FROM
            barang
                JOIN
            (SELECT 
                MAX(id) max_id, barang_id
            FROM
                pembelian_detail
            GROUP BY barang_id) AS t_pd ON t_pd.barang_id = barang.id
                JOIN
            pembelian_detail pd ON pd.id = t_pd.max_id
                JOIN
            (SELECT 
                MAX(id) max_id, barang_id
            FROM
                barang_harga_jual
            GROUP BY barang_id) AS t_hj ON t_hj.barang_id = barang.id
                JOIN
            barang_harga_jual bhj ON bhj.id = t_hj.max_id
                LEFT JOIN
            (SELECT 
                MAX(id) max_id, barang_id
            FROM
                barang_harga_jual_rekomendasi
            GROUP BY barang_id) AS t_hjr ON t_hjr.barang_id = barang.id
                LEFT JOIN
            barang_harga_jual_rekomendasi bhjr ON bhjr.id = t_hjr.max_id
                JOIN
            barang_kategori kat ON kat.id = barang.kategori_id
            {$sqlSup}
        WHERE
            barang.status = :statusBarang
            {$sqlOrder}
                ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':statusBarang', Barang::STATUS_AKTIF);
        
        if (!empty($this->profilId)) {
            $command->bindValue(':supplierId', $this->profilId);
        }

        ini_set('memory_limit', '-1'); //Barang banyak akan menghabiskan memory
        return $command->queryAll();
    }

    public function reportKeCsv($report)
    {
        //$report = $this->reportDaftarBarang();
        $csv = '"barcode","nama","kategori","hpp","harga_jual","rrp"' . PHP_EOL;
        foreach ($report as $baris) {
            $csv.= "\"{$baris['barcode']}\","
                    . "\"{$baris['nama']}\","
                    . "\"{$baris['nama_kategori']}\","
                    . "\"{$baris['hpp']}\","
                    . "\"{$baris['harga_jual']}\","
                    . "\"{$baris['rrp']}\""
                    . PHP_EOL;
        }

        return $csv;
    }

    public function filterKategori()
    {
        return ['' => '[SEMUA]'] + CHtml::listData(KategoriBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama');
    }

    public function listSortBy()
    {
        return [
            self::SORT_BY_BARCODE => 'Barcode [a-z]',
            self::SORT_BY_BARCODE_DSC => 'Barcode [z-a]',
            self::SORT_BY_NAMA => 'Nama [a-z]',
            self::SORT_BY_NAMA_DSC => 'Nama [z-a]',
                //self::SORT_BY_KATEGORI => 'Kategori [a-z]',
                //self::SORT_BY_KATEGORI_DSC => 'Kategori [z-a]',
        ];
    }

    public function listSortBy2()
    {
        return [
            self::SORT_BY_BARCODE => 'barang.barcode',
            self::SORT_BY_BARCODE_DSC => 'barang.barcode desc',
            self::SORT_BY_NAMA => 'barang.nama',
            self::SORT_BY_NAMA_DSC => 'barang.nama desc',
                //self::SORT_BY_KATEGORI => 'Kategori [a-z]',
                //self::SORT_BY_KATEGORI_DSC => 'Kategori [z-a]',
        ];
    }

}
