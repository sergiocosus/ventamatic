<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 9/05/16
 * Time: 12:08 AM
 */

namespace Ventamatic\Http\Controllers\User;


use Carbon\Carbon;
use Auth;
use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\Branch\Sale;
use Ventamatic\Core\User\Schedule;
use Ventamatic\Core\User\ScheduleStatus;
use Ventamatic\Core\User\User;
use Ventamatic\Http\Controllers\Controller;
use Exception;
use DB;

class ScheduleController extends Controller
{
    public function getCurrent()
    {
        /* TODO Fill this method*/

    }

    public function post(Request $request, User $user, Branch $branch)
    {

       /* TODO validar que un usuario no pueda crear más turnos si ya tiene uno con status 1*/
        $initial_amount = $request->get('initial_amount');
        \Log::alert($initial_amount);
        $schedule_status = ScheduleStatus::findOrFail(ScheduleStatus::INITIAL);
        \Log::alert($schedule_status);


        $schedule = Schedule::doSchedule(
            $user,
            $branch,
            $schedule_status,
            $initial_amount);
        return $this->success(compact('schedule'));

    }

    public function patch(Request $request, User $user)
    {
        /* TODO validar que el costo final coincida con el sistema */
        \Log::alert('Inicio patch Schedule');
        $schedule = $user->schedules()
            ->whereScheduleStatusId(ScheduleStatus::INITIAL)
            ->first();

        \Log::alert('Schedule con id inicial:' . $schedule);
        if ($schedule) {
            $sales = $user->sales()
                ->where('created_at', '>=', $schedule->created_at)
                ->where('created_at', '<=', Carbon::now())
                ->get();
            $total = 0;

            /** @var Sale $sale */
            foreach ($sales as $sale) {
                \Log::alert('sale:' . $sale->total);
                $total += $sale->total;
            }
            \Log::alert('total Schedule:' . $total);
            try {
                DB::beginTransaction();
                $schedule->system_amount = $total;
                $calculated_cash=$schedule->initial_amount + $total;
                $schedule->final_amount = $request->get('final_amount');
                $schedule->schedule_status_id=ScheduleStatus::FINAL;
                $schedule->update();
                \Log::alert('fin schedule 2:'.$schedule);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
            return $this->success(compact('schedule'));

        }
        return 'Error haciendo schedule';
    }
}