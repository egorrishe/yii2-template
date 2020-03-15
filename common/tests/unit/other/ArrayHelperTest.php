<?php

namespace common\tests\unit\other;

use common\helpers\ArrayHelper;
use Yii;

class ArrayHelperTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    public function testArray_iunique()
    {
        $items  = ['sss', 'Sss', 'ZZZ', 'zzz', 'fff'];
        $expect = ['sss',   2 => 'ZZZ',   4 => 'fff'];
        
        $res = ArrayHelper::array_iunique($items);
        
        verify($res)->equals($expect);
    }
}
