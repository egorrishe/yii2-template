<?php
namespace common\traits\models;

trait MyIsamTrait
{
    /**
     * If one of tables is MyISAM - we need to delete references manually.
     *
     * Usage: Override method {@see ActiveRecord::afterDelete()} and call this.
     *
     * @param string       $table
     * @param string|array $condition
     *
     * @return int number of deleted rows.
     * @throws \yii\db\Exception
     */
    private function onDeleteCascade(string $table, $condition)
    {
        $params = [];

        $sql = self::getDb()
            ->queryBuilder
            ->delete($table, $condition, $params);

        return self::getDb()->createCommand($sql, $params)->execute();
    }

}