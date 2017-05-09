<?php

/**
 * ReportHarianForm class.
 * ReportHarianForm is the data structure for keeping
 * report harian form data. It is used by the 'harian' action of 'ReportController'.
 *
 * The followings are the available model relations:
 * @property Profil $profil
 */
class ReportHarianForm extends CFormModel
{

    const KERTAS_LETTER = 10;
    const KERTAS_A4 = 20;
    const KERTAS_FOLIO = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA = 'A4';
    const KERTAS_FOLIO_NAMA = 'Folio';

    public $tanggal;
    public $groupByProfil = true;
    public $kertas;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('tanggal', 'required', 'message' => '{attribute} tidak boleh kosong'),
            array('groupByProfil', 'safe'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'tanggal' => 'Tanggal',
            'groupByProfil' => 'Grup per nama profil'
        );
    }

    /**
     * Report Harian Detail
     * @return array Nilai-nilai yang diperlukan untuk report harian
     */
    public function reportHarianDetail()
    {
        $date = isset($this->tanggal) ? date_format(date_create_from_format('d-m-Y', $this->tanggal), 'Y-m-d') : NULL;
        $laporanHarian = LaporanHarian::model()->find('tanggal=:tanggal', array(':tanggal' => $date));
        if (is_null($laporanHarian)) {
            /* Object, tidak untuk disimpan, hanya untuk mencari nilai per tanggal */
            $laporanHarian = new LaporanHarian;
            $laporanHarian->tanggal = date_format(date_create_from_format('d-m-Y', $this->tanggal), 'Y-m-d');
        } else {
            /* fixme: ganti afterFind() */
            $laporanHarian->tanggal = date_format(date_create_from_format('d-m-Y', $laporanHarian->tanggal), 'Y-m-d');
        }
        /* laporannya bisa digrup per nama profil */
        $laporanHarian->groupByProfil = $this->groupByProfil;
        return array(
            'saldoAwal' => $laporanHarian->saldoAwal(),
            'saldoAkhir' => $laporanHarian->saldoAkhir(),
            'saldoAkhirAsli' => $laporanHarian->saldo_akhir,
            'keterangan' => $laporanHarian->keterangan,
            /* ========================================================== */
            'penjualanTunai' => $laporanHarian->penjualanTunai(),
            'totalPenjualanTunai' => $laporanHarian->totalPenjualanTunai(),
            'penjualanPiutang' => $laporanHarian->penjualanPiutang(),
            'totalPenjualanPiutang' => $laporanHarian->totalPenjualanPiutang(),
            'penjualanBayar' => $laporanHarian->penjualanBayar(),
            'totalPenjualanBayar' => $laporanHarian->totalPenjualanBayar(),
            /* ========================================================== */
            'margin' => $laporanHarian->marginPenjualanTunai(),
            'totalMargin' => $laporanHarian->totalMarginPenjualanTunai(),
            /* ========================================================== */
            /* Margin Penjualan Tunai dan Margin Penjualan Piutang */
            'totalMarginPenjualan' => $laporanHarian->totalMarginPenjualan(),
            /* ========================================================== */
            'pembelianTunai' => $laporanHarian->pembelianTunai(),
            'totalPembelianTunai' => $laporanHarian->totalPembelianTunai(),
            'pembelianHutang' => $laporanHarian->pembelianHutang(),
            'totalPembelianHutang' => $laporanHarian->totalPembelianHutang(),
            'pembelianBayar' => $laporanHarian->pembelianBayar(),
            'totalPembelianBayar' => $laporanHarian->totalPembelianBayar(),
            /* ========================================================== */
            'itemPengeluaran' => $laporanHarian->itemPengeluaran(),
            'itemPenerimaan' => $laporanHarian->itemPenerimaan(),
            /* ========================================================== */
            'returBeliTunai' => $laporanHarian->returBeliTunai(),
            'totalReturBeliTunai' => $laporanHarian->totalReturBeliTunai(),
            'returBeliPiutang' => $laporanHarian->returBeliPiutang(),
            'totalReturBeliPiutang' => $laporanHarian->totalReturBeliPiutang(),
            'returBeliBayar' => $laporanHarian->returBeliBayar(),
            'totalReturBeliBayar' => $laporanHarian->totalReturBeliBayar(),
            /* ========================================================== */
            'returJualTunai' => $laporanHarian->returJualTunai(),
            'totalReturJualTunai' => $laporanHarian->totalReturJualTunai(),
            'returJualHutang' => $laporanHarian->returJualHutang(),
            'totalReturJualHutang' => $laporanHarian->totalReturJualHutang(),
            'returJualBayar' => $laporanHarian->returJualBayar(),
            'totalReturJualBayar' => $laporanHarian->totalReturJualBayar(),
        );
    }

    /**
     * Report Harian Rekap
     * @return array Nilai-nilai yang diperlukan untuk report harian
     */
    public function reportHarianRekap()
    {
        $date = isset($this->tanggal) ? date_format(date_create_from_format('d-m-Y', $this->tanggal), 'Y-m-d') : NULL;
        $laporanHarian = LaporanHarian::model()->find('tanggal=:tanggal', array(':tanggal' => $date));
        if (is_null($laporanHarian)) {
            /* Object, tidak untuk disimpan, hanya untuk mencari nilai per tanggal */
            $laporanHarian = new LaporanHarian;
            $laporanHarian->tanggal = date_format(date_create_from_format('d-m-Y', $this->tanggal), 'Y-m-d');
        } else {
            /* fixme: ganti afterFind() */
            $laporanHarian->tanggal = date_format(date_create_from_format('d-m-Y', $laporanHarian->tanggal), 'Y-m-d');
        }
        return array(
            'saldoAwal' => $laporanHarian->saldoAwal(),
            'saldoAkhir' => $laporanHarian->saldoAkhir(),
            'saldoAkhirAsli' => $laporanHarian->saldo_akhir,
            'keterangan' => $laporanHarian->keterangan,
            /* ========================================================== */
            //'penjualanTunai' => $laporanHarian->penjualanTunai(),
            'totalPenjualanTunai' => $laporanHarian->totalPenjualanTunai(),
            //'penjualanPiutang' => $laporanHarian->penjualanPiutang(),
            'totalPenjualanPiutang' => $laporanHarian->totalPenjualanPiutang(),
            //'penjualanBayar' => $laporanHarian->penjualanBayar(),
            'totalPenjualanBayar' => $laporanHarian->totalPenjualanBayar(),
            /* ========================================================== */
            //'margin' => $laporanHarian->marginPenjualanTunai(),
            'totalMargin' => $laporanHarian->totalMarginPenjualanTunai(),
            /* ========================================================== */
            //'pembelianTunai' => $laporanHarian->pembelianTunai(),
            'totalPembelianTunai' => $laporanHarian->totalPembelianTunai(),
            //'pembelianHutang' => $laporanHarian->pembelianHutang(),
            'totalPembelianHutang' => $laporanHarian->totalPembelianHutang(),
            //'pembelianBayar' => $laporanHarian->pembelianBayar(),
            'totalPembelianBayar' => $laporanHarian->totalPembelianBayar(),
            /* ========================================================== */
            'itemPengeluaran' => $laporanHarian->itemPengeluaran(),
            'itemPenerimaan' => $laporanHarian->itemPenerimaan(),
            /* ========================================================== */
            //'returBeliTunai' => $laporanHarian->returBeliTunai(),
            'totalReturBeliTunai' => $laporanHarian->totalReturBeliTunai(),
            //'returBeliPiutang' => $laporanHarian->returBeliPiutang(),
            'totalReturBeliPiutang' => $laporanHarian->totalReturBeliPiutang(),
            //'returBeliBayar' => $laporanHarian->returBeliBayar(),
            'totalReturBeliBayar' => $laporanHarian->totalReturBeliBayar(),
            /* ========================================================== */
            //'returJualTunai' => $laporanHarian->returJualTunai(),
            'totalReturJualTunai' => $laporanHarian->totalReturJualTunai(),
            //'returJualHutang' => $laporanHarian->returJualHutang(),
            'totalReturJualHutang' => $laporanHarian->totalReturJualHutang(),
            //'returJualBayar' => $laporanHarian->returJualBayar(),
            'totalReturJualBayar' => $laporanHarian->totalReturJualBayar(),
        );
    }

    public static function listKertas()
    {
        return [
            self::KERTAS_A4 => self::KERTAS_A4_NAMA,
            self::KERTAS_FOLIO => self::KERTAS_FOLIO_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA
        ];
    }

}
