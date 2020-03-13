<?php
namespace frontend\tests\acceptance;

use frontend\tests\AcceptanceTester;
use yii\helpers\Url;

class HomeCest
{
    public function checkHome(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/blog/default/index'));
        $I->see('My Application');

        $I->seeLink('About');
        $I->click('About');
        if (method_exists($I, 'wait')) {
            $I->wait(3); // only for selenium
        }

        $I->see('This is the About page.');
    }
}
