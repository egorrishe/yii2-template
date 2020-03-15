<?php
namespace common\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * It is case insensitive {@see array_unique()}
     *
     * @param array $items
     *
     * @return array
     */
    public static function array_iunique(array $items): array
    {
        $itemsLower = [];
        foreach ($items as $v) {
            $itemsLower[] = mb_strtolower($v, 'utf8');
        }

        return array_intersect_key($items, array_unique($itemsLower));
    }
}