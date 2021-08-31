<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Illuminate\Http\Request;
use App\Models\Contest;

class CalendarController extends BaseController
{
    public function download(Request $request)
    {
        try {
            $contest = Contest::all()->where('name', $request->input('soutez'))->first();
            $tz = config('app.timezone');
            $name = $contest->name . ' (' . config('app.name') . ')';
            $event = (new Event())
                ->setSummary($name)
                ->setOccurrence(
                    new TimeSpan(
                        new DateTime(
                            \DateTimeImmutable::createFromFormat('Y-m-d H:i:s e', $contest->contest_start . ' ' . $tz), $tz
                        ),
                        new DateTime(
                            \DateTimeImmutable::createFromFormat('Y-m-d H:i:s e', $contest->contest_end . ' ' . $tz), $tz
                        ),
                    )
                );

            $calendar = new Calendar([ $event ]);

            $componentFactory = new CalendarFactory();
            $calendarComponent = $componentFactory->createCalendar($calendar);

            return response($calendarComponent)
                ->withHeaders([
                    'Content-Type' => 'text/calendar; charset=utf-8',
                    'Content-Disposition' => 'attachment; filename="' . $name . '"',
                ]);
        } catch (\Exception $e) {
            $request->session()->flash('errors', array('Něco se pokazilo.'));
        }
        return redirect(route('index'));
    }
}
