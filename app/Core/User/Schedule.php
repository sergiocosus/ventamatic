<?php namespace Ventamatic\Core\User;

use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\System\RevisionableBaseModel;
use DB;
use Exception;

class Schedule extends RevisionableBaseModel {
    
    protected $fillable = ['initial_amount'];

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

    public static function doSchedule(User $user, Branch $branch,
                                      ScheduleStatus $scheduleStatus,
                                      $initial_amount)
    {

        \Log::alert('Inicio Schedule');
        $schedule = new self();
        $schedule->user()->associate($user);
        $schedule->branch()->associate($branch);
        $schedule->scheduleStatus()->associate($scheduleStatus);
        try {
            DB::beginTransaction();

        $schedule->initial_amount=$initial_amount;
        \Log::alert('initial_amount'.$initial_amount);

        $schedule->save();
            \Log::alert('fin de schedule:'.$schedule);
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $schedule;


    }


}
