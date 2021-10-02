<?php

namespace App\Http;

use Log;
use Carbon\Carbon;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Storage;
use ReCaptcha\ReCaptcha;

use App\Exceptions\ForbiddenException;
use App\Models\Contest;

class Utilities {
    public static function checkRecaptcha($request) {
        // FIXME: Workaround due to some mocking issues in PHPUnit when unit testing
        if (config('app.env') == 'testing') {
            return;
        }
        $recaptcha = new ReCaptcha(config('ctvero.recaptchaSecret'));
        $response = $recaptcha->setScoreThreshold(config('ctvero.recaptchaScoreThreshold'))
            ->verify($request->input('g-recaptcha-response'), $request->ip());
        Log::info('Received reCAPTCHA response:', [ var_export($response, true) ]);
        if (! $response->isSuccess()) {
            throw new ForbiddenException();
        }
        return $response;
    }

    public static function contestL10n($contestName) {
        if (preg_match_all('|^(.*\S)\s+(\d+)\s*$|', $contestName, $output)) {
            return __($output[1][0]) . ' ' . $output[2][0];
        }
        return $contestName;
    }

    public static function normalDateTime($dateTime) {
        return date('j. n. Y H:i', strtotime($dateTime));
    }

    public static function contestInProgress($contestName) {
        $contestsInProgress = Contest::submissionActiveOrdered();
        return $contestsInProgress->where('name', $contestName)->first() ? '<i> (' . 'průběžné pořadí' . ')</i>' : '';
    }

    public static function submissionDeadline() {
        $deadline = Carbon::parse(Contest::submissionActiveOrdered()->min('submission_end'));
        $now = Carbon::now();
        if ($deadline->diffInSeconds($now) > 0) {
            if ($deadline->diffInDays($now) > 0) {
                $diff = $deadline->diffInDays($now);
                $unit = 'dnů';
            } elseif ($deadline->diffInHours($now) > 0) {
                $diff = $deadline->diffInHours($now);
                $unit = 'hodin';
            } else {
                $diff = $deadline->diffInMinutes($now);
                $unit = 'minut';
            }
            return 'Zbývá ' . $unit . ': ' . $diff;
        }
    }

    public static function getAppContent($section) {
        $locale = app('translator')->getLocale();
        return Markdown::parse(Storage::get('content/' . $section . '_' . $locale . '.md'));
    }

    public static function gpsToLocator($lon, $lat) {
        $lon += 180;
        $lat += 90;

        $lon1 = chr(65 + intdiv((intval($lon)), 20));
        $lat1 = chr(65 + intdiv((intval($lat)), 10));

        $lon2 = strval(intdiv(intval($lon) % 20, 2));
        $lat2 = strval(intval($lat) % 10);

        $lon3 = chr(65 + floor((fmod($lon, 2)) / (2 / 24)));
        $lat3 = chr(65 + floor((fmod($lat, 1)) / (1 / 24)));

        $locator = $lon1 . $lat1 . $lon2 . $lat2 . $lon3 . $lat3;

        return $locator;
    }

    public static function locatorToGps($locator) {
        list($lon1, $lat1, $lon2, $lat2, $lon3, $lat3) = str_split($locator);

        $lon_base = -180;
        $lat_base = -90;

        $lon1_contrib = (ord($lon1) - 65) * 20;
        $lat1_contrib = (ord($lat1) - 65) * 10;

        $lon2_contrib = intval($lon2) * 2;
        $lat2_contrib = intval($lat2);

        $lon3_contrib = (ord($lon3) - 65) * (2 / 24);
        $lat3_contrib = (ord($lat3) - 65) * (1 / 24);

        return [$lon_base + $lon1_contrib + $lon2_contrib + $lon3_contrib + 1 / 24,
                $lat_base + $lat1_contrib + $lat2_contrib + $lat3_contrib + 1 / 48];
    }
}
