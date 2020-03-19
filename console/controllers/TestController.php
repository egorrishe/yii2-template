<?php

namespace console\controllers;



class TestController extends \yii\console\Controller
{
    function actionSss() {
        $dbUrl = getenv("JAWSDB_MARIA_URL");
        $aUrl = parse_url($dbUrl);


        print_r([
            '$dbUrl'   => $dbUrl,
            '$aUrl'    => $aUrl,
            '$dbname'  => substr($aUrl["path"], 1),
            '$dbname_' => ltrim($aUrl['path'], '/'),
        ]);

    }


}
