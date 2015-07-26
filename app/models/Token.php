<?php
/**
 *
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    26.07.2015
 * @license CC BY-SA 4.0
 */

/**
 * Class Token
 */
class Token extends OmenUUIDModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Tokens';

    protected $primaryKey = ["uuid", "user_id"];

    /**
     * Returns the user for which the token is created.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }
}