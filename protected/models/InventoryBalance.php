<?php

/**
 * This is the model class for table "inventory_balance".
 *
 * The followings are the available columns in table 'inventory_balance':
 * @property string $id
 * @property integer $asal
 * @property string $nomor_dokumen
 * @property string $barang_id
 * @property string $harga_beli
 * @property integer $qty_awal
 * @property integer $qty
 * @property string $pembelian_detail_id
 * @property string $retur_penjualan_detail_id
 * @property string $stock_opname_detail_id
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property StockOpnameDetail $stockOpnameDetail
 * @property Barang $barang
 * @property PembelianDetail $pembelianDetail
 * @property PenjualanDetail $penjualanDetail
 * @property User $updatedBy
 * @property PenjualanDetail[] $penjualanDetails
 */
class InventoryBalance extends CActiveRecord
{

    const ASAL_PEMBELIAN = 1;
    const ASAL_RETURJUAL = 2;
    const ASAL_SO = 3;

    public $jumlah;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'inventory_balance';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('asal, barang_id, qty_awal, qty', 'required'),
            array('asal, qty_awal, qty', 'numerical', 'integerOnly' => true),
            array('nomor_dokumen', 'length', 'max' => 45),
            array('barang_id, pembelian_detail_id, retur_penjualan_detail_id, stock_opname_detail_id, updated_by', 'length', 'max' => 10),
            array('harga_beli', 'length', 'max' => 18),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, nomor_dokumen, barang_id, harga_beli, qty_awal, qty, pembelian_detail_id, retur_penjualan_detail_id, stock_opname_detail_id, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'barang' => array(self::BELONGS_TO, 'Barang', 'barang_id'),
            'pembelianDetail' => array(self::BELONGS_TO, 'PembelianDetail', 'pembelian_detail_id'),
            'returPenjualanDetail' => array(self::BELONGS_TO, 'ReturPenjualanDetail', 'retur_penjualan_detail_id'),
            'stockOpnameDetail' => array(self::BELONGS_TO, 'StockOpnameDetail', 'stock_opname_detail_id'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'asal' => 'Asal',
            'nomor_dokumen' => 'Dokumen',
            'barang_id' => 'Barang',
            'harga_beli' => 'Harga Beli',
            'qty_awal' => 'Qty Awal',
            'qty' => 'Qty',
            'pembelian_detail_id' => 'Pembelian Detail',
            'retur_penjualan_detail_id' => 'Retur Penjualan Detail',
            'stock_opname_detail_id' => 'Stock Opname Detail',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($defaultOrder = NULL)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('asal', $this->asal);
        $criteria->compare('nomor_dokumen', $this->nomor_dokumen, true);
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('harga_beli', $this->harga_beli, true);
        $criteria->compare('qty_awal', $this->qty_awal);
        $criteria->compare('qty', $this->qty);
        $criteria->compare('pembelian_detail_id', $this->pembelian_detail_id, true);
        $criteria->compare('retur_penjualan_detail_id', $this->retur_penjualan_detail_id, true);
        $criteria->compare('stock_opname_detail_id', $this->stock_opname_detail_id, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $orderBy = is_null($defaultOrder) ? 't.id desc' : $defaultOrder;
        $sort = array(
            'defaultOrder' => $orderBy
        );

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort,
            'pagination' => array('pageSize' => 5),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return InventoryBalance the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {

        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date("Y-m-d H:i:s");
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    /**
     * Cari inventory layer terakhir dari $barangId
     * @param int $barangId ID Barang
     * @return static the record found. Null if none is found.
     */
    public function layerTerakhir($barangId)
    {
        return InventoryBalance::model()->findBySql("
         select *
         from inventory_balance
         where id = (select max(id)
                     from inventory_balance
                     where barang_id = {$barangId})");
    }

    public function beli($asal, $nomorDokumen, $pembelianDetailId, $barangId, $hargaBeli, $qty)
    {
        $this->asal = $asal;
        $this->nomor_dokumen = $nomorDokumen;
        $this->pembelian_detail_id = $pembelianDetailId;
        $this->barang_id = $barangId;
        $this->harga_beli = $hargaBeli;
        $this->qty_awal = $qty;
        $this->qty = $qty;

        $layerTerakhir = $this->layerTerakhir($barangId);
        if (!is_null($layerTerakhir)) {
            /*
             * Jika layer terakhir nilainya <=0, 0 kan qty nya.
             * Sesuaikan qty layer saat ini
             */
            if ($layerTerakhir->qty <= 0) {
                /* fix me: Jika penjualan menyimpan harga beli di harga_beli_temp, update harga_beli dengan harga beli terbaru */
                $this->qty += $layerTerakhir->qty;
                $layerTerakhir->qty = 0;
                if (!$layerTerakhir->save()) {
                    throw new Exception("Gagal simpan layer terakhir");
                }
            }
        }

        if ($this->save()) {
            return true;
        } else {
            throw new Exception("Gagal simpan layer inventory");
        }
    }

    public function jual($barangId, $qty)
    {
        $inventories = InventoryBalance::model()->findAll(array(
            'condition' => 'barang_id=:barangId and qty <>0',
            'order' => 'id',
            'params' => array(':barangId' => $barangId)));

        if (empty($inventories)) {
            /* Jika kosong cari lagi inventory terakhir */
            $layerTerakhir = $this->layerTerakhir($barangId);
            /* Jika kosong juga, berarti belum ada proses pembelian ?? */
            if (is_null($layerTerakhir)) {
                throw new Exception('Inventory barang tidak ditemukan, lakukan pembelian terlebih dahulu', 500);
            }
            /* Variabel $inventories diisi hanya dengan layer terakhir */
            $inventories = array($layerTerakhir);
        }

        $inventoryTerpakai = array();
        $layer = count($inventories);
        $curLayer = $layer;
        $sisa = $qty;
        foreach ($inventories as $inventory) {
            $curLayer--;
            $qtyTerpakai = 0;
            /* Jika sudah tidak ada sisa. Keluar */
            if ($sisa == 0) {
                break;
            }

            /*
             * Jika inventory > 0. Stok ADA
             * sisa selalu > 0
             */
            if ($inventory->qty > $sisa) {

                /* Inventory cukup. 0 (nol) kan sisa, kurangi inventory */
                $inventory->qty -= $sisa;
                $qtyTerpakai = $sisa;
                $sisa = 0;
            } else if ($inventory->qty <= $sisa && $inventory->qty > 0) {
                /*
                 * Inventory kurang (tapi masih positif). Kurangi sisa. 0 (nol) kan.
                 */
                $qtyTerpakai = $inventory->qty;
                $sisa -= $inventory->qty;
                /* Inventory di 0 (nol) kan */
                $inventory->qty = 0;
            }

            /*
             * Jika ada qtyTerpakai, catat.
             */
            if ($qtyTerpakai > 0) {
                $inventoryTerpakai[] = array(
                    'id' => $inventory->id,
                    'pembelianDetailId' => $inventory->pembelian_detail_id,
                    'hargaBeli' => $inventory->harga_beli,
                    'qtyTerpakai' => $qtyTerpakai,
                );
            }

            /*
             * Jika inventory layer terakhir dan
             * Jika inventory <= 0. Stok MINUS/HABIS
             * 0 kan sisa, sesuaikan inventory
             */
            if (0 === $curLayer && $inventory->qty <= 0 && $sisa > 0) {
                /* Kurangi inventory. 0 (nol) kan sisa */
                $inventory->qty -= $sisa;
                $inventoryTerpakai[] = array(
                    'id' => $inventory->id,
                    'pembelianDetailId' => $inventory->pembelian_detail_id,
                    'hargaBeli' => $inventory->harga_beli,
                    'qtyTerpakai' => $sisa,
                    'negatif' => true
                );
                $sisa = 0;
            }


            /*
             * Simpan inventory
             */
            if (!$inventory->save()) {
                throw new Exception("Gagal simpan inventory#{$inventory->id} qty {$inventory->qty}", 500);
            }
        }
        return $inventoryTerpakai;
    }

    /**
     * Mengurangi stok melalui proses returBeli: Mencocokkan pembelian yang
     * dipilih
     * @param object $returBeliDetail Retur Beli Detail yang akan diproses
     * @throws Exception Jika inventory tidak bisa disimpan atau stok tidak cukup
     */
    public function returBeli($returBeliDetail)
    {
        $inventoryBalance = InventoryBalance::model()->findByPk($returBeliDetail->inventory_balance_id);
        $inventoryTerpakai = array();
        $sisa = $returBeliDetail->qty;
        $qtyTerpakai = 0;

        if ($inventoryBalance->qty >= $sisa) {
            $qtyTerpakai = $sisa;
            $inventoryBalance->qty -= $sisa;
            $sisa = 0;
        } else {
            $qtyTerpakai = $inventoryBalance->qty;
            $sisa -= $inventoryBalance->qty;
            $inventoryBalance->qty = 0;
        }

        $inventoryTerpakai[] = array(
            'id' => $inventoryBalance->id,
            'qtyTerpakai' => $qtyTerpakai,
            'pembelianDetailId' => $inventoryBalance->pembelian_detail_id
        );

        /*
         * Simpan inventory
         */
        if (!$inventoryBalance->save()) {
            throw new Exception("Gagal simpan inventory#{$inventoryBalance->id} qty {$inventoryBalance->qty}", 500);
        }






//      $pembelianDetail = PembelianDetail::model()->findByPk($returBeliDetail->pembelian_detail_id);
//      $barangId = $pembelianDetail->barang_id;
////		$inventories = InventoryBalance::model()->findAll('barang_id=:barangId and qty <> 0 order by id', array(
////			 ':barangId' => $barangId,
////		));
//
//      $inventories = InventoryBalance::model()->findAll(array(
//          'condition' => 'barang_id=:barangId and qty <>0',
//          'order' => 'id',
//          'params' => array(':barangId' => $barangId)));
//
//      if (empty($inventories)) {
//         /* Jika kosong cari lagi inventory terakhir */
//         $layerTerakhir = $this->layerTerakhir($barangId);
//         /* Jika kosong juga, berarti belum ada proses pembelian ?? */
//         if (is_null($layerTerakhir)) {
//            throw new Exception('Inventory barang tidak ditemukan, lakukan pembelian terlebih dahulu', 500);
//         }
//         /* Variabel $inventories diisi hanya dengan layer terakhir */
//         /* Seharusnya tidak ke sini !!, jika ini terjadi berarti
//          * STOK MINUS: Retur beli untuk barang yang tidak ada stok nya !! */
//         $inventories = array($layerTerakhir);
//      }
//
//      $inventoryTerpakai = array();
//      $layer = count($inventories);
//      $curLayer = $layer;
//      $sisa = $returBeliDetail->qty;
//      $ketemu = false; // Untuk mencatat permulaan layer inventory yang sesuai dengan pilihan
//      foreach ($inventories as $inventory) {
//         //print_r($inventory);
//         $curLayer--;
//         $qtyTerpakai = 0;
//         /* Jika sudah tidak ada sisa. Keluar */
//         if ($sisa == 0) {
//            break;
//         }
//
//         /* Jika layernya sesuai, atau sudah ketemu (layer setelah ketemu) */
//         if ($inventory->pembelian_detail_id == $pembelianDetail->id || $ketemu) {
//            $ketemu = true;
//
//            if ($inventory->qty > $sisa) {
//               /* Inventory cukup. 0 (nol) kan sisa, kurangi inventory */
//               $inventory->qty -= $sisa;
//               $qtyTerpakai = $sisa;
//               $sisa = 0;
//            } else if ($inventory->qty <= $sisa && $inventory->qty > 0) {
//               /*
//                * Inventory kurang (tapi masih positif). Kurangi sisa. 0 (nol) kan.
//                */
//               $qtyTerpakai = $inventory->qty;
//               $sisa -= $inventory->qty;
//               /* Inventory di 0 (nol) kan */
//               $inventory->qty = 0;
//            }
//
//            /*
//             * Jika ada qtyTerpakai, catat.
//             */
//            if ($qtyTerpakai > 0) {
//               $inventoryTerpakai[] = array(
//                   'id' => $inventory->id,
//                   'qtyTerpakai' => $qtyTerpakai,
//                   'pembelianDetailId' => $inventory->pembelian_detail_id
//               );
//            }
//
//
//            /* Ini seharusnya TIDAK terjadi
//             * Jika inventory layer terakhir dan
//             * Jika inventory <= 0. Stok MINUS/HABIS
//             * 0 kan sisa, sesuaikan inventory
//             */
//            /*
//              if (0 === $curLayer && $inventory->qty <= 0) {
//              // Kurangi inventory. 0 (nol) kan sisa
//              $inventory->qty -= $sisa;
//              $inventoryTerpakai[] = array(
//              'id' => $inventory->id,
//              'hargaBeli' => $inventory->harga_beli,
//              'qtyTerpakai' => $sisa,
//              'negatif' => true
//              );
//              $sisa = 0;
//              }
//             */
//
//            /*
//             * Simpan inventory
//             */
//            if (!$inventory->save()) {
//               throw new Exception("Gagal simpan inventory#{$inventory->id} qty {$inventory->qty}", 500);
//            }
//         }
//      }
        /* Jika ternyata masih ada sisa
         * Ulangi lagi cari inventory dari awal
         * Karena sebelumnya (di atas) mengurangi inventory dimulai dari layer yang cocok
         * dengan pembelian yang dipilih
         * FIX ME
         */
        if ($sisa > 0) {
            throw new Exception("Stok {$returBeliDetail->inventoryBalance->barang->nama} tidak cukup untuk diretur", 500);
        }

        return $inventoryTerpakai;
    }

    /**
     * Menambah layer inventory baru untuk proses retur penjualan,
     * sesuai HPP (Harga Pokok Penjualan) dari penjualan yang dipilih.
     * Tapi jika current layer minus, penambahan qty dimasukkan ke layer yang minus terlebih dahulu
     * @param object $returPenjualanDetail Objek Model ReturPenjualanDetail
     * @return boolean true jika berhasil menambah inventory
     * @throws Exception
     */
    public function returJual($returPenjualanDetail)
    {
        $hpps = HargaPokokPenjualan::model()->findAll('penjualan_detail_id=:penjualanDetail', array(':penjualanDetail' => $returPenjualanDetail->penjualan_detail_id));
        $sisa = $returPenjualanDetail->qty;
        foreach ($hpps as $hpp) {
            if ($sisa == 0) {
                break;
            }

            $inventoryBalance = new InventoryBalance;
            $inventoryBalance->asal = InventoryBalance::ASAL_RETURJUAL;
            $inventoryBalance->nomor_dokumen = $returPenjualanDetail->returPenjualan->nomor;
            $inventoryBalance->pembelian_detail_id = $hpp->pembelian_detail_id;
            $inventoryBalance->retur_penjualan_detail_id = $returPenjualanDetail->id;
            $inventoryBalance->barang_id = $returPenjualanDetail->penjualanDetail->barang_id;
            $inventoryBalance->harga_beli = $hpp->harga_beli;

            /* Jika hpp jumlahnya cukup, nol kan sisa. catat qtynya di inventory */
            if ($hpp->qty >= $sisa) {
                $inventoryBalance->qty_awal = $sisa;
                $inventoryBalance->qty = $sisa;
                $sisa = 0;
            } else {
                /* Jika hpp tidak cukup, catat di inventory secukupnya, cari lagi di hpp berikutnya (jika ada) */
                $inventoryBalance->qty_awal = $hpp->qty;
                $inventoryBalance->qty = $hpp->qty;
                $sisa -= $hpp->qty;
            }

            $layerTerakhir = $this->layerTerakhir($returPenjualanDetail->penjualanDetail->barang_id);
            if (!is_null($layerTerakhir) && $layerTerakhir->qty <= 0) {
                /*
                 * Jika layer terakhir nilainya <=0, 0 kan qty nya.
                 * Sesuaikan qty layer saat ini
                 */
                $inventoryBalance->qty += $layerTerakhir->qty;
                $layerTerakhir->qty = 0;
                if (!$layerTerakhir->save()) {
                    throw new Exception("Gagal simpan layer terakhir");
                }
            }

            if ($inventoryBalance->save()) {
                return true;
            } else {
                throw new Exception("Gagal simpan layer inventory");
            }
        }

        /* FIX ME: Jika masih ada sisa,
         * berarti qty barang yang diretur lebih banyak dari qty barang yang di jual ??
         * cari di penjualan berikutnya */
        if ($sisa > 0) {
            throw new Exception("Retur jual lebih banyak dari penjualan: barang=" . $returPenjualanDetail->penjualanDetail->barang->nama);
        }
    }

    public function so($soModel, $soDetail)
    {
        $selisih = $soDetail->qty_sebenarnya - $soDetail->qty_tercatat;
        if ($selisih > 0) {
            $this->soPlus($soModel, $soDetail->id, $soDetail->barang_id, $selisih);
        } else if ($selisih < 0) {
            $this->soMinus($soDetail->barang_id, $selisih);
        } else {
            /* Jika selisih 0, nothing to do */
        }
    }

    public function soMinus($barangId, $selisih)
    {
        $inventories = InventoryBalance::model()->findAll(array(
            'condition' => 'barang_id=:barangId and qty <>0',
            'order' => 'id',
            'params' => array(':barangId' => $barangId)));

        if (empty($inventories)) {
            /* Jika kosong cari lagi inventory terakhir */
            $layerTerakhir = $this->layerTerakhir($barangId);
            /* Jika kosong juga, berarti belum ada proses pembelian ?? */
            if (is_null($layerTerakhir)) {
                throw new Exception('[SO-]Inventory barang ID#'.$barangId.' tidak ditemukan, lakukan pembelian terlebih dahulu', 500);
            }
            /* Variabel $inventories diisi hanya dengan layer terakhir */
            $inventories = array($layerTerakhir);
        }

        $layer = count($inventories);
        $curLayer = $layer;
        $sisa = abs($selisih);
        foreach ($inventories as $inventory) {
            $curLayer--;
            /* Jika sudah tidak ada sisa. Keluar */
            if ($sisa == 0) {
                break;
            }

            /*
             * Jika inventory > 0. Stok ADA
             * sisa selalu > 0
             */
            if ($inventory->qty > $sisa) {

                /* Inventory cukup. 0 (nol) kan sisa, kurangi inventory */
                $inventory->qty -= $sisa;
                $sisa = 0;
            } else if ($inventory->qty <= $sisa && $inventory->qty > 0) {
                /*
                 * Inventory kurang (tapi masih positif). Kurangi sisa. 0 (nol) kan.
                 */
                $sisa -= $inventory->qty;
                /* Inventory di 0 (nol) kan */
                $inventory->qty = 0;
            }

            /*
             * Mission Imposible: SO stok minus
             * Jika inventory layer terakhir dan
             * Jika inventory <= 0. Stok MINUS/HABIS
             * 0 kan sisa, sesuaikan inventory
             */
            if (0 === $curLayer && $inventory->qty <= 0) {
                /* Kurangi inventory. 0 (nol) kan sisa */
                $inventory->qty -= $sisa;
                $sisa = 0;
            }


            /*
             * Simpan inventory
             */
            if (!$inventory->save()) {
                throw new Exception("Gagal simpan inventory#{$inventory->id} qty {$inventory->qty}", 500);
            }
        }
    }

    public function soPlus($soModel, $soDetailId, $barangId, $selisih)
    {
        $inventory = InventoryBalance::model()->find(array(
            'condition' => 'barang_id=:barangId and qty <>0',
            'order' => 'id',
            'params' => array(':barangId' => $barangId)));

        if (is_null($inventory)) {
            /* Jika kosong cari lagi inventory terakhir */
            $layerTerakhir = $this->layerTerakhir($barangId);
            /* Jika kosong juga, berarti belum ada proses pembelian ?? */
            if (is_null($layerTerakhir)) {
                throw new Exception('[SO+] Inventory barang ID#'.$barangId.' tidak ditemukan, lakukan pembelian terlebih dahulu', 500);
            }
            /* Variabel $inventory diisi dengan layer terakhir */
            $inventory = $layerTerakhir;
        }
        $sisa = $selisih;

        $kapasitasInventory = $inventory->qty_awal - $inventory->qty;
        if ($kapasitasInventory != 0) {
            if ($sisa < $kapasitasInventory) {
                $inventory->qty+=$sisa;
                $sisa = 0;
            } else {
                $inventory->qty = $inventory->qty_awal;
                $sisa-=$kapasitasInventory;
            }

            /*
             * Simpan inventory
             */
            if (!$inventory->save()) {
                throw new Exception("Gagal simpan inventory#{$inventory->id} qty {$inventory->qty}", 500);
            }
        }

        if ($sisa > 0) {
            $this->soInvSebelumnya($soModel, $soDetailId, $inventory->id, $inventory->barang_id, $sisa, $inventory->pembelian_detail_id, $inventory->harga_beli);
        }
    }

    public function soInvSebelumnya($soModel, $soDetailId, $invId, $barangId, $selisih, $pembelianDetailIdTerakhir, $hargaBeli = null)
    {
        $sisa = $selisih;
        $inventory = InventoryBalance::model()->find(array(
            'condition' => 'barang_id=:barangId and id < :invId',
            'order' => 'id',
            'params' => array(':barangId' => $barangId, ':invId' => $invId)));

        if (is_null($inventory)) {
            //throw new Exception("Layer inventory tidak ditemukan lagi", 500);

            /* Karena ada kasus, migrasi dari aplikasi lama, yang stoknya lebih
             * kecil dibanding stok fisik, maka diperlukan menambah layer inventory
             * yang berasal dari SO / tidak dari pembelian, dan tidak menimbulkan hutang
             */
            $i = new InventoryBalance;
            $i->barang_id = $barangId;
            $i->harga_beli = $hargaBeli;
            $i->qty_awal = $selisih;
            $i->qty = $selisih;
            $i->asal = self::ASAL_SO;
            $i->nomor_dokumen = $soModel->nomor;
            $i->pembelian_detail_id = $pembelianDetailIdTerakhir; // Diisi dengan pembelian terakhir, untuk kompatibilitas dg proses lain
            $i->stock_opname_detail_id = $soDetailId;
            if (!$i->save()) {
                throw new Exception("Gagal membuat layer dari SO", 500);
            }
            $sisa = 0;
        } else {

            $kapasitasInventory = $inventory->qty_awal - $inventory->qty;
            if ($kapasitasInventory != 0) {
                if ($sisa < $kapasitasInventory) {
                    $inventory->qty+=$sisa;
                    $sisa = 0;
                } else {
                    $inventory->qty = $inventory->qty_awal;
                    $sisa-=$kapasitasInventory;
                }

                /*
                 * Simpan inventory
                 */
                if (!$inventory->save()) {
                    throw new Exception("Gagal simpan inventory#{$inventory->id} qty {$inventory->qty}", 500);
                }
            }
        }
        if ($sisa > 0) {
            $this->soInvSebelumnya($soModel, $soDetailId, $inventory->id, $inventory->barang_id, $sisa, $inventory->pembelian_detail_id, $inventory->harga_beli);
        }
    }

    public function getNamaAsal()
    {
        switch ($this->asal) {

            case InventoryBalance::ASAL_PEMBELIAN:
                return 'Pembelian';

            case InventoryBalance::ASAL_RETURJUAL:
                return 'Retur Jual';

            case InventoryBalance::ASAL_SO:
                return 'SO';
        }
    }

    /**
     * Mencari harga beli layer pertama yang belum habis di inventory
     * @param int $barangId
     * @return decimal harga beli
     */
    public function getHargaBeliAwal($barangId)
    {
        $inventory = InventoryBalance::model()->find(array(
            'condition' => 'barang_id=:barangId and qty <>0',
            'order' => 'id',
            'params' => array(':barangId' => $barangId)));

        /* Jika tidak ada stok yang pantas (stok = 0 semua)
         * maka cari inventory yang paling baru
         */
        if (is_null($inventory)) {
            $inventory = InventoryBalance::model()->find(array(
                'condition' => 'barang_id=:barangId',
                'order' => 'id desc',
                'params' => array(':barangId' => $barangId)));
        }
        return is_null($inventory) ? 0 : $inventory->harga_beli;
    }

    /**
     * Mengembalikan nama controller tergantung dari asal dokumen
     * @param type $asal ID asal dokumen
     * @return string nama controller dari asal
     */
    public function namaAsalController()
    {
        switch ($this->asal) {

            case InventoryBalance::ASAL_PEMBELIAN:
                return 'pembelian';

            case InventoryBalance::ASAL_RETURJUAL:
                return 'returpenjualan';

            case InventoryBalance::ASAL_SO;
                return 'stockopname';
        }
    }

    public function modelAsal()
    {
        switch ($this->asal) {

            case InventoryBalance::ASAL_PEMBELIAN:
                return Pembelian::model()->find("nomor={$this->nomor_dokumen}");

            case InventoryBalance::ASAL_RETURJUAL:
                return ReturPenjualan::model()->find("nomor={$this->nomor_dokumen}");

            case InventoryBalance::ASAL_SO;
                return StockOpname::model()->find("nomor={$this->nomor_dokumen}");
        }
    }

    public function totalInventory()
    {
        $inventory = Yii::app()->db->createCommand()->
                select('sum(harga_beli * qty) total')->
                from('inventory_balance')->
                where('qty > 0')->
                queryRow();
        return $inventory['total'];
    }

}
