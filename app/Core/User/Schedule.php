<?php namespace Ventamatic\Core\User;

use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\System\RevisionableBaseModel;

class Schedule extends RevisionableBaseModel {
    
    protected $fillable = ['id', 'user_id', 'branch_id', 'initial_amount', 
        'system_amount', 'final_amount', 'schedule_status_id'];


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
