<?php
/**
 * UUID Model base class
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    26.07.2015
 * @license CC BY-SA 4.0
 */

/**
 * UUID Model base class
 */
class OmenUUIDModel extends OmenModel
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Attach to the 'creating' Model Event to provide a UUID
         * for the `id` field (provided by $model->getKeyName())
         */
        static::creating(function ($model) {
            $model->uuid = (string)$model->generateNewId();
        });
    }

    /**
     * Get a new version 4 (random) UUID.
     *
     * @return
     */
    public function generateNewId()
    {
        return Uuid::generate(4);
    }
}