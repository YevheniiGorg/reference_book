<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m201116_092224_roles
 */
class m201116_092224_roles extends Migration
{
    public function up()
    {
        $authManager = Yii::$app->authManager;

        $authManager->removeAll();

        $user = $authManager->createRole(User::ROLE_USER);
        $authManager->add($user);

        $admin = $authManager->createRole(User::ROLE_ADMINISTRATOR);
        $authManager->add($admin);

        $authManager->addChild($admin, $user);

        $authManager->assign($admin, 1);
        $authManager->assign($user, 2);
    }

    /**
     * @return bool|void
     */
    public function down()
    {
        $authManager = Yii::$app->authManager;

        $authManager->remove($authManager->getRole(User::ROLE_ADMINISTRATOR));
        $authManager->remove($authManager->getRole(User::ROLE_USER));
    }
}
