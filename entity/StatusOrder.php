<?php

namespace app\entity;

use yii\db\ActiveRecord;

/**
 * Таблица dir_status справочник критериев
 * @property int id идентификатор
 * @property string title текст статуса
 * @property string description описание статуса
 * @property int type_id тип статуса
 * @property int order порядок статуса
 * @package app\entity
 */
class StatusOrder extends ActiveRecord
{
    public function getType()
    {
        return $this->hasOne(DirStatusType::className(), ['id' => 'type_id']);
    }
}