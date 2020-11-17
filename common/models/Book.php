<?php

namespace common\models;

use common\behaviors\DateToTimeBehavior;
use common\traits\StatusTrait;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $short_description
 * @property int|null $publication_date
 * @property string|null $image_src_filename
 * @property string|null $image_web_filename
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property WriterBook[] $writerBooks
 */
class Book extends \yii\db\ActiveRecord
{

    /**
     * @return array statuses list
     * STATUS_PUBLISHED = 1;
     * STATUS_DRAFT     = 0;
     */
    use StatusTrait;

    /**
     * @var array
     */
    public $image;
    public $writers_arr;
    public $date__event_at_formatted;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'title', 'status'], 'required'],
            [['short_description'], 'string'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['date__event_at_formatted'], 'default', 'value' => function () {
                return date(DATE_ISO8601);
            }],
            [['date__event_at_formatted'], 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            [['slug', 'title'], 'string', 'max' => 512],
            [['image_src_filename', 'image_web_filename'], 'string', 'max' => 1024],
            [['image'], 'file', 'extensions'=>'jpg, png'],
            [['image'], 'file', 'maxSize'=>'2000000'],
            [['image', 'writers_arr'], 'safe'],
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
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'immutable' => true,
                'ensureUnique'=>true
            ],
            [
                'class' => DateToTimeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => 'date__event_at_formatted',
                    ActiveRecord::EVENT_AFTER_FIND => 'date__event_at_formatted',
                ],
                'timeAttribute' => 'publication_date',
                'format' => 'd.m.Y H:i'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'ЧПУ',
            'title' => 'Название',
            'image' => 'Картинка',
            'short_description' => 'Короткое описание',
            'publication_date' => 'Дата публикации книги',
            'status' => 'Статус',
            'created_at' => 'Создан в',
            'updated_at' => 'Обновлено в',
            'created_by' => 'Создан',
            'updated_by' => 'Обновлено',
        ];
    }

    /**
     * Gets query for [[Writer]] via table [[WriterBooks]]
     *
     * @return \yii\db\ActiveQuery|\common\models\query\WriterQuery
     */
    public function getWriters()
    {
        return $this->hasMany(Writer::className(), ['id' => 'writer_id'])->
        viaTable(WriterBook::tableName(), ['book_id' => 'id']);

    }

    /**
     * Gets query for [[WriterBooks]].
     *
     * @return \yii\db\ActiveQuery|\common\models\WriterBook
     */
    public function getWriterBooks()
    {
        return $this->hasMany(WriterBook::className(), ['book_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\BookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BookQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * after Save
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (is_array($this->writers_arr) && !empty($this->writers_arr)) {
            WriterBook::deleteAll(['book_id' => $this->id]);
            $val = [];
            foreach ($this->writers_arr as $key => $value) {
                $val[] = [ $value, $this->id];
            }
            self::getDb()->createCommand()
                ->batchInsert(WriterBook::tableName(), ['writer_id', 'book_id'], $val)->execute();
        }
    }

    /**
     * after find
     */
    public function afterFind()
    {

        $this->writers_arr = $this->writers;
        parent::afterFind();
    }

}
