<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m201116_092342_init_permissions
 */
class m201116_092342_init_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $authManager = Yii::$app->authManager;
        $administratorRole = $authManager->getRole(User::ROLE_ADMINISTRATOR);

        $loginToBackend = $authManager->createPermission('loginToBackend');
        $authManager->add($loginToBackend);

        $authManager->addChild($administratorRole, $loginToBackend);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $authManager = Yii::$app->authManager;
        $authManager->remove($authManager->getPermission('loginToBackend'));
    }
}
