<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m201116_093621_seed_data
 */
class m201116_093621_seed_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(User::tableName(), [
            'id' => 1,
            'username' => 'webmaster',
            'email' => 'webmaster@example.com',
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('webmaster'),
            'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
            'access_token' => Yii::$app->getSecurity()->generateRandomString(40),
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        $this->insert(User::tableName(), [
            'id' => 2,
            'username' => 'user',
            'email' => 'user@example.com',
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('user'),
            'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
            'access_token' => Yii::$app->getSecurity()->generateRandomString(40),
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(512)->notNull(),
            'title' => $this->string(512)->notNull(),
            'short_description' => $this->text(),
            'publication_date' =>$this->integer(),
            'image_src_filename' => $this->string(1024),
            'image_web_filename' => $this->string(1024),
            'status' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        $this->createTable('{{%writer}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(512)->notNull(),
            'second_name' => $this->string(512)->notNull(),
            'middle_name' => $this->string(512),
            'status' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        $this->createTable('{{%writer_book}}', [
            'writer_id' => $this->integer(),
            'book_id' => $this->integer(),

        ]);

        $this->addForeignKey('fk_writer_writer_book', '{{%writer_book}}', 'writer_id', '{{%writer}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_book_writer_book', '{{%writer_book}}', 'book_id', '{{%book}}', 'id', 'cascade', 'cascade');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user}}', [
            'id' => [1, 2]
        ]);

        $this->dropForeignKey('fk_writer_writer_book', '{{%writer_book}}');
        $this->dropForeignKey('fk_book_writer_book', '{{%writer_book}}');

        $this->dropTable('{{%book}}');
        $this->dropTable('{{%writer}}');
        $this->dropTable('{{%writer_book}}');
    }

}
