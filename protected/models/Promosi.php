<?php

/**
 * This is the model class for table "promosi".
 *
 * The followings are the available columns in table 'promosi':
 * @property string $id
 * @property string $nama
 * @property string $dari
 * @property string $sampai
 * @property string $nominal
 * @property double $persen
 * @property integer $khusus_member
 * @property integer $prioritas
 * @property integer $semua_barang
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property User $updatedBy
 */
class Promosi extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'promosi';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nama, dari, sampai', 'required'),
            array('khusus_member, prioritas, semua_barang, status', 'numerical', 'integerOnly' => true),
            array('persen', 'numerical'),
            array('nama', 'length', 'max' => 255),
            array('nominal', 'length', 'max' => 18),
            array('updated_by', 'length', 'max' => 10),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, nama, dari, sampai, nominal, persen, khusus_member, prioritas, semua_barang, status, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
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
            'nama' => 'Nama',
            'dari' => 'Dari',
            'sampai' => 'Sampai',
            'nominal' => 'Nominal',
            'persen' => 'Persen',
            'khusus_member' => 'Khusus Member',
            'prioritas' => 'Prioritas',
            'semua_barang' => 'Semua Barang',
            'status' => 'Status',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('dari', $this->dari, true);
        $criteria->compare('sampai', $this->sampai, true);
        $criteria->compare('nominal', $this->nominal, true);
        $criteria->compare('persen', $this->persen);
        $criteria->compare('khusus_member', $this->khusus_member);
        $criteria->compare('prioritas', $this->prioritas);
        $criteria->compare('semua_barang', $this->semua_barang);
        $criteria->compare('status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Promosi the static model class
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
        $this->updated_at = null; // Trigger current timestamp
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

}
