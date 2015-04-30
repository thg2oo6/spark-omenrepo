<?php
/**
 * The keywords model class for the Omen repository.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @license CC BY-SA 4.0
 */

/**
 * The keywords model class for the Omen repository.
 */
class Keyword extends OmenModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Keywords';

    /**
     * Returns the projects attached to the current keyword.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany('Project', 'Project_Keyword');
    }

} 