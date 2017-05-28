<?php

namespace Ventamatic\Policies;

use Ventamatic\Core\User\Schedule;
use Ventamatic\Core\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the schedule.
     *
     * @return mixed
     */
    public function makeNote(User $user, Schedule $schedule)
    {
       return $schedule->user_id === $user->id;
    }


}
