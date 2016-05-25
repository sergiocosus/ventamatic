<?php namespace Ventamatic\Core\User;

use Ventamatic\Core\System\RevisionableBaseModel;

class ScheduleStatus extends RevisionableBaseModel {

    protected $fillable = ['id', 'name'];

    protected $casts = [
        'id' => 'integer',
    ];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }


}
