<?php

/**
 * ReportPlsForm class.
 * ReportPlsForm is the data structure for keeping
 * report PLS form data. It is used by the 'pls' action of 'ReportController'.
 *
 * The followings are the available model relations:
 */
class ReportPlsForm extends CFormModel
{
    const SORT_BY_SISA_HARI_ASC = 1;
    const SORT_BY_SISA_HARI_DSC = 2;
    /* ============= */
    const KERTAS_LETTER = 10;
    const KERTAS_A4     = 20;
    const KERTAS_FOLIO  = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA     = 'A4';
    const KERTAS_FOLIO_NAMA  = 'Folio';

    public $jumlahHari;
    public $profilId;
    public $sisaHariMax;
    public $rakId;
    public $sortBy;
    public $kertas;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['jumlahHari, sortBy, sisaHariMax', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['profilId, rakId, kertas', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'jumlahHari'  => 'Range Analisa Penjualan',
            'profilId'    => 'Profil (Opsional)',
            'sisaHariMax' => 'Limit Estimasi Sisa Hari <=',
            'sortBy'      => 'Urut berdasarkan',
        ];
    }

    public function getNamaProfil()
    {
        $model = Profil::model()->findByPk($this->profilId);
        return $model->nama;
    }

    public function reportPls()
    {
        $command = Yii::app()->db->createCommand();
        $command->select("
            t_jualan.*,
            barang.barcode,
            barang.nama,
            t_jualan.qty / :range ads,
            t_stok.qty stok,
            t_stok.qty / (t_jualan.qty / :range) sisa_hari
                ");
        $command->from("
            (SELECT
                barang_id, SUM(qty) qty
            FROM
                penjualan_detail
            WHERE
                created_at BETWEEN DATE_SUB(NOW(), INTERVAL :range DAY) AND NOW()
            GROUP BY barang_id) AS t_jualan
                ");
        $command->join("
            (SELECT
                barang_id, SUM(qty) qty
            FROM
                inventory_balance
            GROUP BY barang_id) AS t_stok
                ", "t_stok.barang_id = t_jualan.barang_id");
        $command->join("barang", "t_jualan.barang_id = barang.id");
        $command->where("t_stok.qty / (t_jualan.qty / :range) <= :sisaHariMax");
        $command->order("t_stok.qty / (t_jualan.qty / :range)" . $this->listNamaSortBy()[$this->sortBy]);

        if (!empty($this->profilId)) {
            $command->join('supplier_barang sb', 'sb.barang_id = t_jualan.barang_id');
            $command->andWhere('sb.supplier_id=:profilId');
            $command->bindValue(":profilId", $this->profilId);
        }

        $command->bindValue(":range", $this->jumlahHari);
        $command->bindValue(":sisaHariMax", $this->sisaHariMax);

        return $command->queryAll();
    }

    public function filterKategori()
    {
        return ['NULL' => '[SEMUA]'] + CHtml::listData(KategoriBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    public function filterRak()
    {
        return ['NULL' => '[SEMUA]'] + CHtml::listData(RakBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    public function listSortBy()
    {
        return [
            self::SORT_BY_SISA_HARI_ASC => 'Sisa Hari [a-z]',
            self::SORT_BY_SISA_HARI_DSC => 'Sisa Hari [z-a]',
        ];
    }

    public function listNamaSortBy()
    {
        return [
            self::SORT_BY_SISA_HARI_ASC => 'asc',
            self::SORT_BY_SISA_HARI_DSC => 'desc',
        ];
    }

    public static function listKertas()
    {
        return [
            self::KERTAS_A4     => self::KERTAS_A4_NAMA,
            self::KERTAS_FOLIO  => self::KERTAS_FOLIO_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA,
        ];
    }

}
