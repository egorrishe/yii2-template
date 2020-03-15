<?php

use common\models\user\User;

$aUser = User::find()
    ->select('username, id')
    ->createCommand()
    ->queryAll(PDO::FETCH_KEY_PAIR);

return [
    [
        //use it only in UserCest::checkOnDeleteCascade() - else it may be deleted
        'title'        => 'for checkOnDeleteCascade only',
        'description'  => 'use it only in UserCest::checkOnDeleteCascade() - else it may be deleted',
        'user_id'      => $aUser['checkOnDeleteCascade'],
        'status'       => '1',
        'created_date' => '1548675330',
        'updated_date' => '1548675330',
        'content'      => 'txt',
    ],
    [
        'title'        => 'Test 1',
        'user_id'      => $aUser['bayer.hudson'],
        'status'       => '1',
        'created_date' => '1548675330',
        'updated_date' => '1548675330',
        'description'  => 'desc',
        'content'      => 'txt',
    ],
];
