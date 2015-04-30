<?php
/**
 * The project version model class for the Omen repository.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @license CC BY-SA 4.0
 */

/**
 * The project version model class for the Omen repository.
 */
class Version extends OmenModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Versions';

    /**
     * Returns the project to which this version is attached.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo('Project');
    }

    public function dependsOn()
    {
        return $this->belongsToMany('Version', 'VersionLinks', 'child_id', 'parent_id');
    }

    public function isDependency()
    {
        return $this->hasMany('Version', 'VersionLinks', 'parent_id', 'child_id');
    }

} 