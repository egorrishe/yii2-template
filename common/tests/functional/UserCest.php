<?php
/** @noinspection PhpUnused */

namespace common\tests\functional;

use common\tests\_fixtures\ArticleFixture;
use common\tests\_fixtures\UserFixture;
use common\models\blog\Article;
use common\models\user\User;
use common\tests\FunctionalTester;

class UserCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user'    => UserFixture::className(),
            'article' => ArticleFixture::className(),
        ];
    }

    /**
     * @param FunctionalTester $I
     *
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function checkOnDeleteCascade(FunctionalTester $I)
    {
        $mUser = User::find()
            ->where(['username' => 'checkOnDeleteCascade'])
            ->with('articles')
            ->one();

        verify('User exist',             $mUser          )->notEmpty();
        verify('User have any Articles', $mUser->articles)->notEmpty();
        verify('User deleted',           $mUser->delete())->notEmpty();

        $I->dontSeeRecord(Article::class, ['user_id' => $mUser->id]);
    }

}
