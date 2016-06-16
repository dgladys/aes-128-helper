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

    /** @var array  */
    protected $oldAttributes = [];

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
            ActiveRecord::EVENT_BEFORE_UPDATE => 'encryptBeforeSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'cleanAfterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'cleanAfterSave',
            ActiveRecord::EVENT_AFTER_FIND => 'decryptAfterFind'
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
        $this->oldAttributes = $owner->getAttributes($this->encryptedAttributes);
        foreach ($this->encryptedAttributes as $attributeName) {
            $owner->setAttribute($attributeName, AES128::encrypt($owner->getAttribute($attributeName)), $this->encryptKey);
        }
    }

    /**
     * Clean data after save
     * @param \yii\base\Event $event
     */
    public function cleanAfterSave($event)
    {
        /** @var \yii\db\ActiveRecord $owner */
        $owner = $this->owner;
        $owner->setAttributes($this->oldAttributes);
    }

    /**
     * Decrypt data after find
     * @param \yii\base\Event $event
     */
    public function decryptAfterFind($event)
    {
        /** @var \yii\db\ActiveRecord $owner */
        $owner = $this->owner;

        foreach ($owner->getAttributes($this->encryptedAttributes) as $attributeName => $value) {
            $owner->setAttribute($attributeName, AES128::decrypt($value, $this->encryptKey));
        }
    }
}