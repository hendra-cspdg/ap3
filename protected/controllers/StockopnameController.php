<?php

class StockopnameController extends Controller
{

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('deny', // deny guest
                'users' => array('guest'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $detail = new StockOpnameDetail('search');
        $detail->unsetAttributes();
        $detail->setAttribute('stock_opname_id', '=' . $id);
        if (isset($_GET['StockOpnameDetail'])) {
            $detail->attributes = $_GET['StockOpnameDetail'];
        }
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'detail' => $detail
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'ubah' page.
     */
    public function actionTambah()
    {
        $this->layout = '//layouts/box_kecil';
        $model = new StockOpname;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['StockOpname'])) {
            $model->attributes = $_POST['StockOpname'];
            if ($model->save())
                $this->redirect(array('ubah', 'id' => $model->id));
        }

        $this->render('tambah', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUbah($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if ($model->status != StockOpname::STATUS_DRAFT) {
            $this->redirect(array('view', 'id' => $model->id));
        }

        $manualMode = isset($_GET['manual']) && $_GET['manual'] == true;

        $soDetail = new StockOpnameDetail('search');
        $soDetail->unsetAttributes();
        if (isset($_GET['StockOpnameDetail'])) {
            $soDetail->attributes = $_GET['StockOpnameDetail'];
        }
        $soDetail->setAttribute('stock_opname_id', "{$id}");

        $barang = new Barang('search');
        $barang->unsetAttributes();
        if (isset($_GET['cariBarang'])) {
            $barang->setAttribute('nama', $_GET['namaBarang']);
        }

        if ($manualMode) {
            $barangBelumSO = new Barang('search');
            $barangBelumSO->unsetAttributes();
            $barangBelumSO->aktif()->belumSO($model->id, $model->rak_id);

            if (isset($_GET['Barang'])) {
                $barangBelumSO->attributes = $_GET['Barang'];
            }
        }

        $scanBarcode = null;
        /* Ada scan dari aplikasi barcode scanner (android) */
        if (isset($_GET['barcodescan'])) {
            $scanBarcode = $_GET['barcodescan'];
        }

        $this->render('ubah', array(
            'model' => $model,
            'soDetail' => $soDetail,
            'barang' => $barang,
            'manualMode' => $manualMode,
            'barangBelumSO' => $manualMode ? $barangBelumSO : NULL,
            'scanBarcode' => $scanBarcode
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionHapus($id)
    {
        $model = $this->loadModel($id);
        if ($model->status == StockOpname::STATUS_DRAFT) {
            StockOpnameDetail::model()->deleteAll('stock_opname_id=:id', [':id' => $id]);
            $model->delete();
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new StockOpname('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockOpname']))
            $model->attributes = $_GET['StockOpname'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return StockOpname the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = StockOpname::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param StockOpname $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'stock-opname-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Untuk render link actionView jika ada nomor, jika belum, string kosong
     * @param obj $data
     * @return string Link ke action view jika ada nomor
     */
    public function renderLinkToView($data)
    {
        $return = '';
        if (isset($data->nomor)) {
            $return = '<a href="' .
                    $this->createUrl('view', array('id' => $data->id)) . '">' .
                    $data->nomor . '</a>';
        }
        return $return;
    }

    /**
     * render link actionUbah jika belum ada nomor
     * @param obj $data
     * @return string tanggal, beserta link jika masih draft (belum ada nomor)
     */
    public function renderLinkToUbah($data)
    {
        if (!isset($data->nomor)) {
            $return = '<a href="' .
                    $this->createUrl('ubah', array('id' => $data->id)) . '">' .
                    $data->tanggal . '</a>';
        } else {
            $return = $data->tanggal;
        }
        return $return;
    }

    public function actionScanBarcode($id)
    {
        $return = array(
            'sukses' => false
        );
        if (isset($_POST['scan'])) {
            $barcode = $_POST['barcode'];
            $barang = Barang::model()->find('barcode=:barcode', array(':barcode' => $barcode));
            $qtySudahSo = StockOpnameDetail::model()->qtyYangSudahSo($id, $barang->id);
            $return = array(
                'sukses' => true,
                'barcode' => $barcode,
                'nama' => $barang->nama,
                'stok' => $barang->getStok(),
                'qtySudahSo' => $qtySudahSo
            );
        }

        $this->renderJSON($return);
    }

    public function actionTambahDetail($id)
    {
        $return = array(
            'sukses' => false
        );
        if (isset($_POST['tambah'])) {
            $barcode = $_POST['barcode'];
            $qty = $_POST['qty'];
            $barang = Barang::model()->find('barcode=:barcode', array(':barcode' => $barcode));
            $return = $this->tambahDetail($id, $barang->id, $barang->getStok(), $qty);
        }
        $this->renderJSON($return);
    }

    /**
     * Tambah detail SO
     * @param int $soId
     * @param int $barangId
     * @param int $qtyTercatat
     * @param int $qtySebenarnya
     * @return array true jika berhasil
     */
    public function tambahDetail($soId, $barangId, $qtyTercatat, $qtySebenarnya)
    {
        $return = array(
            'sukses' => false
        );
        $detail = new StockOpnameDetail;
        $detail->stock_opname_id = $soId;
        $detail->barang_id = $barangId;
        $detail->qty_tercatat = is_null($qtyTercatat) ? 0 : $qtyTercatat;
        $detail->qty_sebenarnya = $qtySebenarnya;
        if ($detail->save()) {
            $return = array(
                'sukses' => true,
            );
        }
        return $return;
    }

    public function actionHapusDetail($id)
    {
        $detail = StockOpnameDetail::model()->findByPk($id);
        if (!$detail->delete()) {
            throw new Exception('Gagal hapus detail SO');
        }
    }

    /*
     * Simpan Stock Opname dan proses terkait (inventory)
     */

    public function actionSimpanSo($id)
    {
        $return = array('sukses' => false);
        // cek jika 'simpan' ada dan bernilai true
        if (isset($_POST['simpan']) && $_POST['simpan']) {
            $so = $this->loadModel($id);
            if ($so->status == StockOpname::STATUS_DRAFT) {
                $return = $so->simpanSo();
            }
        }
        $this->renderJSON($return);
    }

    public function renderQtyLinkEditable($data, $row)
    {
        $ak = '';
        if ($row == 0) {
            $ak = 'accesskey="q"';
        }
        return '<a href="#" class="editable-qty" data-type="text" data-pk="' . $data->id . '" ' . $ak . ' data-url="' .
                Yii::app()->controller->createUrl('inputqtymanual') . '">' .
                'Input..' . '</a>';
    }

    /**
     * Input qty manual via ajax
     */
    public function actionInputQtyManual()
    {
        $return = array(
            'sukses' => false,
            'error' => array(
                'code' => '500',
                'msg' => 'Sempurnakan input!',
            )
        );
        if (isset($_POST['pk'])) {
            $pk = $_POST['pk'];
            $qtyInput = $_POST['value'];
            $id = $_POST['soId'];
            $barang = Barang::model()->findByPk($pk);

            $return = $this->tambahDetail($id, $barang->id, $barang->getStok(), $qtyInput);
        }

        $this->renderJSON($return);
    }

    public function renderGantiRakLinkEditable($data, $row)
    {
        return CHtml::link('Pilih..', '', array(
                    'class' => 'editable-rak',
                    'data-type' => 'select',
                    'data-pk' => $data->id,
                    'data-url' => Yii::app()->controller->createUrl('gantirak'),
                    'data-title' => 'Select Rak'
        ));
    }

    public function actionGantiRak()
    {
        $return = array(
            'sukses' => false,
            'error' => array(
                'code' => '500',
                'msg' => 'Sempurnakan input!',
            )
        );
        if (isset($_POST['pk'])) {
            $pk = $_POST['pk'];
            $rakId = $_POST['value'];
            $barang = Barang::model()->findByPk($pk);
            Barang::model()->updateByPk($pk, array('rak_id' => $rakId));
            $return = array('sukses' => true);
        }

        $this->renderJSON($return);
    }

    public function renderTombolSetNol($data, $row)
    {
        return CHtml::link('<i class="fa fa-square-o"><i>', Yii::app()->controller->createUrl('setnol'), array(
                    'data-barangid' => $data->id,
                    'class' => 'tombol-setnol'
        ));
    }

    public function actionSetNol($id)
    {
        $return = array('sukses' => false);
        if (isset($_POST['barangid'])) {
            $pk = $_POST['barangid'];
            $barang = Barang::model()->findByPk($pk);
            $return = $this->tambahDetail($id, $barang->id, $barang->getStok(), 0);
        }
        $this->renderJSON($return);
    }

    public function renderTombolSetInAktif($data, $row)
    {
        return CHtml::link('<i class="fa fa-square-o"><i>', Yii::app()->controller->createUrl('setinaktif'), array(
                    'data-barangid' => $data->id,
                    'class' => 'tombol-setinaktif'
        ));
    }

    public function actionSetInAktif($id)
    {
        $return = array('sukses' => false);
        if (isset($_POST['barangid'])) {
            $pk = $_POST['barangid'];
            $barang = Barang::model()->findByPk($pk);
            Barang::model()->updateByPk($pk, array('status' => Barang::STATUS_TIDAK_AKTIF));
            $return = array('sukses' => true);
        }
        $this->renderJSON($return);
    }

    public function actionSetNolAll($id)
    {
        $model = $this->loadModel($id);

        $this->renderJSON([
            'sukses' => true,
            'rows' => $model->tambahDetailSetNol()
        ]);
    }

    public function actionSetInAktifAll($id)
    {
        $model = $this->loadModel($id);

        $this->renderJSON([
            'sukses' => true,
            'rows' => $model->setInAktifAll()
        ]);
    }

}
