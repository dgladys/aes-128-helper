<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2016-06-16
 * Time: 12:49
 */

namespace dgladys\aes128helper;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class AES128Behavior
 * @package dgladys\aes128helper
 */
class AES128Behavior extends Behavior
{
    /**
     * List of model attributes that will be encrypted/decrypted when are saving to/loading from database
     * @var array
     */
    public $encryptedAttributes = [];

    /**
     * @var string 15-chars key
     */
    public $encryptKey = null;

    /**
     * @return array
     */
    public function getEncryptedAttributes()
    {
        return $this->encryptedAttributes;
    }

    /**
     * @param array $encryptedAttributes
     */
    public function setEncryptedAttributes($encryptedAttributes)
    {
        $this->encryptedAttributes = $encryptedAttributes;
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'encryptBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'encryptBeforeSave'
        ];
    }

    /**
     * Encrypt data before save to database
     * @param \yii\base\Event $event
     */
    public function encryptBeforeSave($event)
    {
        /** @var \yii\db\ActiveRecord $owner */
        $owner = $this->owner;
        foreach ($this->encryptedAttributes as $attributeName) {
            $owner->setAttribute($attributeName, AES128::encrypt($owner->getAttribute($attributeName)), $this->encryptKey);
        }
    }
}