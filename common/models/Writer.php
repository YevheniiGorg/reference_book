<?php

namespace common\models;

use common\traits\StatusTrait;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "writer".
 *
 * @property int $id
 * @property string $first_name
 * @property string $second_name
 * @property string|null $middle_name
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property WriterBook[] $writerBooks
 */
class Writer extends \yii\db\ActiveRecord
{

    /**
     * @return array statuses list
     * STATUS_PUBLISHED = 1;
     * STATUS_DRAFT     = 0;
     */
    use StatusTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%writer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'second_name', 'status'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['first_name', 'second_name', 'middle_name'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'Имя',
            'second_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'status' => 'Статус',
            'created_at' => 'Создан в',
            'updated_at' => 'Обновлено в',
            'created_by' => 'Создан',
            'updated_by' => 'Обновлено',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }

    /**
     * Gets query for [[WriterBooks]].
     *
     * @return \yii\db\ActiveQuery|\common\models\WriterBook
     */
    public function getWriterBooks()
    {
        return $this->hasMany(WriterBook::className(), ['writer_id' => 'id']);
    }

    /**
     * @return string Writer full name
     */
    public function getFullName()
    {
        return $this->second_name . ' ' . $this->first_name . ' ' . $this->middle_name;
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\WriterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\WriterQuery(get_called_class());
    }

    /**
     * Get array map (id, fullName)
     * @return array
     */
    public static function getMapFullName(){
        $array_map = ArrayHelper::map(self::find()->all(), 'id', 'fullName');
        return $array_map;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

}
