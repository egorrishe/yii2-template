<?php

namespace console\controllers;



use yii\helpers\VarDumper;

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
            '\Yii::$app->db->dsn' => \Yii::$app->db->dsn,
            '\Yii::$app->db->username' => \Yii::$app->db->username,
            '\Yii::$app->db->password' => \Yii::$app->db->password,
        ]);


        print_r(
            \Yii::$app->db->createCommand('SHOW SCHEMAS ;')->queryColumn()
        );
    }


}
