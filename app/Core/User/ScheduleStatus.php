<?php namespace Ventamatic\Core\User;

use Ventamatic\Core\System\RevisionableBaseModel;

class ScheduleStatus extends RevisionableBaseModel {

    protected $fillable = ['id', 'name'];


    public function schedules() {
        return $this->hasMany(Schedule::class);
    }


}
