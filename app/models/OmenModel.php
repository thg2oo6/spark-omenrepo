<?php
/**
 * The base model class for the Omen repository.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @license CC BY-SA 4.0
 */

use Illuminate\Database\Eloquent\Builder;


/**
 * The base model class for the Omen repository.
 */
class OmenModel extends Eloquent
{
    /**
     * Perform a model update operation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array                                 $options
     *
     * @return bool|null
     */
    protected function performUpdate(Builder $query, array $options = [])
    {
        $dirty = $this->getDirty();

        if (count($dirty) > 0) {
            // If the updating event returns false, we will cancel the update operation so
            // developers can hook Validation systems into their models and cancel this
            // operation if the model does not pass validation. Otherwise, we update.
            if ($this->fireModelEvent('updating') === false) {
                return false;
            }

            // First we need to create a fresh query instance and touch the creation and
            // update timestamp on the model which are maintained by us for developer
            // convenience. Then we will just continue saving the model instances.
            if ($this->timestamps && array_get($options, 'timestamps', true)) {
                $this->updateTimestamps();
            }

            /* -- Composite Key Fix -- */
            $primary = (count($this->getKeyName()) > 1) ? $this->getKeyName() : [$this->getKeyName()];
            $uq = array_intersect_key($this->original, array_flip($primary));

            $uq = !empty($uq) ? $uq : array_intersect_key($this->getAttributes(), array_flip($primary));

            $query->where($uq);
            /* -- End Composite Key Fix -- */

            // Once we have run the update operation, we will fire the "updated" event for
            // this model instance. This will allow developers to hook into these after
            // models are updated, giving them a chance to do any special processing.
            $dirty = $this->getDirty();

            if (count($dirty) > 0) {
                $query->update($dirty);
//                $this->setKeysForSaveQuery($query)->update($dirty);

                $this->fireModelEvent('updated', false);
            }
        }

        return true;
    }

    /**
     * Perform the actual delete query on this model instance.
     *
     * @return void
     */
    protected function performDeleteOnModel()
    {
        /* -- Composite Key Fix -- */
        $primary = (count($this->getKeyName()) > 1) ? $this->getKeyName() : [$this->getKeyName()];
        $uq = array_intersect_key($this->original, array_flip($primary));

        $uq = !empty($uq) ? $uq : array_intersect_key($this->getAttributes(), array_flip($primary));
        /* -- End Composite Key Fix -- */

        $this->newQuery()->where($uq)->delete();
    }
} 