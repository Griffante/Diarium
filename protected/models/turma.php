<?php

/**
 * This is the model class for table "turma".
 *
 * The followings are the available columns in table 'turma':
 * @property integer $id
 * @property string $nome
 * @property integer $disciplina
 * @property integer $professor
 */
class turma extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'turma';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nome', 'required'),
			array('id, disciplina, professor', 'numerical', 'integerOnly'=>true),
			array('nome', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nome, disciplina, professor', 'safe', 'on'=>'search'),
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
			'diario' => array(self::HAS_ONE, 'diario_de_classe', 'turma'),
			'lista_de_alunoses' => array(self::HAS_MANY, 'lista_de_alunos', 'Turma'),
			'professor0' => array(self::BELONGS_TO, 'Professor', 'professor'),
			'disciplina0' => array(self::BELONGS_TO, 'Disciplina', 'disciplina'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'nome' => 'Nome',
			'disciplina' => 'Disciplina',
			'professor' => 'Professor',
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
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		$criteria->compare('nome',$this->nome,true);

		$criteria->compare('disciplina',$this->disciplina);

		$criteria->compare('professor',$this->professor);

		return new CActiveDataProvider('turma', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return turma the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}