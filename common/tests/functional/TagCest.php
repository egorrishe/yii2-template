<?php
/** @noinspection PhpUnused */

namespace common\tests\functional;

use common\tests\_fixtures\Tag2ArticleFixture;
use common\tests\_fixtures\TagFixture;
use common\models\blog\Tag;
use common\models\blog\Tag2Article;
use common\tests\FunctionalTester;

class TagCest
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
            'tag'         => TagFixture::className(),
            'tag2article' => Tag2ArticleFixture::className(),
        ];
    }

    public function _after(FunctionalTester $I)
    {
        //MyISAM tables should be cleaned manually
        Tag::deleteAll();
        Tag2Article::deleteAll();
    }

    /**
     * @param FunctionalTester $I
     *
     * @throws \yii\db\IntegrityException
     */
    public function checkDbCollationIsCaseInsensitive(FunctionalTester $I)
    {
        verify_that(
            'PHP' === Tag::findOne(['content' => 'PHP'])->content
        );

        try {
            $I->haveRecord(Tag::class, ['content' => 'php']);
        } catch (\yii\db\IntegrityException $e) {
            //error "Integrity constraint violation" - is ok
            if ((int)$e->getCode() !== 23000) {
                throw $e;
            }
        }
        verify('This insert must throw IntegrityException', $e ?? null)->notEmpty();
    }

    public function checkModelRulesOnSave(FunctionalTester $I)
    {
        verify_that(
            'PHP' === Tag::findOne(['content' => 'PHP'])->content
        );

        $mTag = new Tag;
        $mTag->content = ' php ';
        verify_not($mTag->validate());
        verify('Model validation must trim whitespaces', $mTag->content)
            ->equals('php');
        verify('Unique validator must be case insensitive', $mTag->errors['content'])
            ->contains('Content "php" has already been taken.');
    }

    /**
     * @param FunctionalTester $I
     *
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function checkOnDeleteCascade(FunctionalTester $I)
    {
        $mTag = Tag::find()
            ->where(['content' => 'checkOnDeleteCascade'])
            ->with('articles')
            ->one();

        verify('Tag exist',             $mTag          )->notEmpty();
        verify('Tag have any Articles', $mTag->articles)->notEmpty();
        verify('Tag deleted',           $mTag->delete())->notEmpty();

        $I->dontSeeRecord(Tag2Article::class, ['tag_id' => $mTag->id]);
    }

}
