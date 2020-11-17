<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "writer_book".
 *
 * @property int|null $writer_id
 * @property int|null $book_id
 *
 * @property Book $book
 * @property Writer $writer
 */
class WriterBook extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'writer_book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['writer_id', 'book_id'], 'integer'],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::className(), 'targetAttribute' => ['book_id' => 'id']],
            [['writer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Writer::className(), 'targetAttribute' => ['writer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'writer_id' => 'Writer ID',
            'book_id' => 'Book ID',
        ];
    }

    /**
     * Gets query for [[Book]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Book::className(), ['id' => 'book_id']);
    }

    /**
     * Gets query for [[Writer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWriter()
    {
        return $this->hasOne(Writer::className(), ['id' => 'writer_id']);
    }
}
