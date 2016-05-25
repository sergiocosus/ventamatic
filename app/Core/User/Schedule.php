<?php namespace Ventamatic\Core\User;

use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\System\RevisionableBaseModel;

class Schedule extends RevisionableBaseModel {
    
    protected $fillable = ['id', 'user_id', 'branch_id', 'initial_amount', 
        'system_amount', 'final_amount', 'schedule_status_id'];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'branch_id' => 'integer',
        'schedule_status_id' => 'integer',
        'initial_amount' => 'double',
        'system_amount' => 'double',
        'final_amount' => 'double',
    ];
    
    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function scheduleStatus() {
        return $this->belongsTo(ScheduleStatus::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }


}
