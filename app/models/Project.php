<?php

/**
 * The project model class for the Omen repository.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @license CC BY-SA 4.0
 */

/**
 * The project model class for the Omen repository.
 */
class Project extends OmenModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Projects';

    /**
     * Returns the keywords attached to the current project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function keywords()
    {
        return $this->belongsToMany('Keyword', 'Project_Keyword');
    }

    /**
     * Returns the versions attached to the current project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function versions()
    {
        return $this->hasMany('Version');
    }

    /**
     * Returns the author of the current project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

} 