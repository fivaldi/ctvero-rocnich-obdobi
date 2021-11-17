<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\Alarm;
use Eluceo\iCal\Domain\ValueObject\Alarm\DisplayAction;
use Eluceo\iCal\Domain\ValueObject\Alarm\EmailAction;
use Eluceo\iCal\Domain\ValueObject\Alarm\RelativeTrigger;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Illuminate\Http\Request;

use App\Exceptions\AppException;
use App\Http\Utilities;
use App\Models\Contest;

class CalendarController extends BaseController
{
    public function download(Request $request)
    {
        if (! $request->input('contest')) {
            throw new AppException(400, array(__('Neúplný požadavek')));
        }

        $contest = Contest::all()->where('name', $request->input('contest'))->first();
        if (empty($contest)) {
            throw new AppException(404, array(__('Soutěž nebyla nalezena.')));
        }

        try {
            $tz = config('app.timezone');
            $summary = __('Čtvero ročních období') . ' - ' . Utilities::contestL10n($contest->name);
            $description = __('CB soutěž') . ' (' . config('app.url') . ')';
            $trigger = new RelativeTrigger(\DateInterval::createFromDateString('0 day ago'));

            $event = (new Event())
                ->setSummary($summary)
                ->setDescription($description)
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
            $event->addAlarm(new Alarm(new DisplayAction($summary), $trigger));
            $event->addAlarm(new Alarm(new EmailAction($summary, $description), $trigger));

            $deadline = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s e', $contest->submission_end . ' ' . $tz);
            $reminder = (new Event())
                ->setSummary($summary . ' - ' . __('Poslední den pro odeslání hlášení'))
                ->setDescription($description)
                ->setOccurrence(new TimeSpan(new DateTime($deadline->add(\DateInterval::createFromDateString('1 day ago')), $tz),
                                             new DateTime($deadline, $tz)));
            $reminder->addAlarm(new Alarm(new DisplayAction($summary . ' - ' . __('Odeslat hlášení')), $trigger));
            $reminder->addAlarm(new Alarm(new EmailAction($summary . ' - ' . __('Odeslat hlášení'), $description), $trigger));

            $calendar = new Calendar([ $event, $reminder ]);

            $componentFactory = new CalendarFactory();
            $calendarComponent = $componentFactory->createCalendar($calendar);

            return response($calendarComponent)
                ->withHeaders([
                    'Content-Type' => 'text/calendar; charset=utf-8',
                    'Content-Disposition' => 'attachment; filename="' . $summary . '"',
                ]);
        } catch (\Exception $e) {
            throw new AppException(500, array(__('Došlo k chybě!')));
        }
    }
}
