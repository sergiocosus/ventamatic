<?php namespace Ventamatic\Core\User;

use Ventamatic\Core\System\RevisionableBaseModel;

class ScheduleStatus extends RevisionableBaseModel {
    const INITIAL = 1;
    const WARNING=2;
    const FINAL=3;
    protected $fillable = ['id', 'name'];

    protected $casts = [
        'id' => 'integer',
    ];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }


}
