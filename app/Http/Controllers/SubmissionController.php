<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DiDom\Document;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Models\Diary;
use App\Models\Category;
use App\Models\Contest;
use App\Rules\Locator;

class SubmissionController extends Controller
{
    function __construct() {
        foreach (config('ctvero.diaryUrlToProcessor') as $diaryUrlTemplate => $processor) {
            $diarySources[] = preg_replace('|^http(s)?://([^/]+)/.*|', '$2', $diaryUrlTemplate);
        }
        $this->diarySources = array_unique($diarySources);
        $this->contests = Contest::submissionActiveOrdered();
        $this->categories = Category::allOrdered();
        $this->messages = [
            'email' => 'Pole :attribute obsahuje neplatnou e-mailovou adresu.',
            'required' => 'Pole :attribute je vyžadováno.',
            'max' => 'Pole :attribute přesahuje povolenou délku :max znaků.',
            'unique' => 'Pole :attribute již obsahuje v databázi stejný záznam.',
            'size' => 'Pole :attribute nemá přesně :size znaků.',
            'integer' => 'Pole :attribute neobsahuje celočíselnou hodnotu.',
            'gt' => 'Pole :attribute neobsahuje hodnotu větší než :value .',
        ];
    }
    public function processCbdxCz() {
        $doc = new Document($this->diaryUrl, true);
        if ($doc->first('table tr:nth-child(1) td')->text() == 'Název expedice') {
            $this->callSign = preg_replace('|^\s+|u', '', $doc->first('table tr:nth-child(1) td:nth-child(2)')->text());
            $this->callSign = trim($this->callSign);
        }
        if ($doc->first('table tr:nth-child(2) td')->text() == 'QTH - místo vysílání') {
            $this->qthName = preg_replace('|^\s+|u', '', $doc->first('table tr:nth-child(2) td:nth-child(2)')->text());
            $this->qthName = trim($this->qthName);
        }
        if ($doc->first('table tr:nth-child(3) td')->text() == 'Lokátor stanoviště') {
            $this->qthLocator = preg_replace('|^\s+|u', '', $doc->first('table tr:nth-child(3) td:nth-child(2)')->text());
            $this->qthLocator = trim($this->qthLocator);
        }
        if ($doc->first('table tr:nth-child(16) td')->text() == 'Počet uskutečněných spojení') {
            $qsoCountWithDesc = $doc->first('table tr:nth-child(16) td:nth-child(2)')->text();
            $this->qsoCount = preg_replace('|^\s+|u', '', $qsoCountWithDesc);
            $this->qsoCount = preg_replace('| spojení$|', '', $this->qsoCount);
            $this->qsoCount = trim($this->qsoCount);
        }
    }
    public function processCbpmrCz() {
        $doc = new Document($this->diaryUrl, true, 'ISO-8859-2');
        if ($doc->first('table.tbl tr:nth-child(5) td')->text() == 'Volačka') {
            $this->callSign = trim($doc->first('table.tbl tr:nth-child(5) td:nth-child(2)')->text());
        }
        if ($doc->first('table.tbl tr:nth-child(6) td')->text() == 'Místo vysílání') {
            $this->qthName = trim($doc->first('table.tbl tr:nth-child(6) td:nth-child(2)')->text());
        }
        if ($doc->first('table.tbl tr:nth-child(7) td')->text() == 'Lokátor stanoviště') {
            $this->qthLocator = trim($doc->first('table.tbl tr:nth-child(7) td:nth-child(2)')->text());
        }
        if ($doc->first('table.tbl tr:nth-child(17) td')->text() == 'Počet spojení') {
            $this->qsoCount = trim($doc->first('table.tbl tr:nth-child(17) td:nth-child(2)')->text());
        }
    }
    public function processCbpmrInfo() {
        $context = stream_context_create([ 'http' => [ 'follow_location' => false ] ]);
        $html = file_get_contents($this->diaryUrl, false, $context);
        $finalUrl = NULL;
        foreach ($http_response_header as $header) {
            if (preg_match('|^Location: /share/[^/]+/\d+|', $header)) {
                $diaryId = trim(preg_replace('|.*/share/[^/]+/(\d+).*|', '$1', $header));
                $finalUrl = config('ctvero.cbpmrInfoApiUrl') . $diaryId;
                break;
            }
        }

        $auth = base64_encode(config('ctvero.cbpmrInfoApiAuthUsername') . ':' . config('ctvero.cbpmrInfoApiAuthPassword'));
        $new_context = stream_context_create([ 'http' => [ 'header' => 'Authorization: Basic ' . $auth ] ]);
        $data = json_decode(file_get_contents($finalUrl, false, $new_context));
        $this->callSign = $data->callName;
        $this->qthName = $data->place;
        $this->qthLocator = $data->locator;
        $this->qsoCount = $data->totalCalls;
    }
    public function show(Request $request)
    {
        return view('submission', [ 'data' => $this ]);
    }
    public function submit(Request $request)
    {
        if ($request->input('step') == 1) {
            try {
                $diaryUrl = trim($request->input('diaryUrl'));
                foreach (config('ctvero.diaryUrlToProcessor') as $diaryUrlTemplate => $processor) {
                    if (preg_match('|^' . preg_quote($diaryUrlTemplate) . '|', $diaryUrl)) {
                        $processor = 'process' . $processor;
                        $this->diaryUrlTemplate = $diaryUrlTemplate;
                        $this->diaryUrl = $diaryUrl;
                        $this->$processor();
                        break;
                    }
                }
                $request->session()->flash('diary', [
                    'Url' => $this->diaryUrl,
                    'callSign' => $this->callSign,
                    'qthName' => $this->qthName,
                    'qthLocator' => $this->qthLocator,
                    'qsoCount' => $this->qsoCount ]);
            } catch (\Exception $e) {
                $request->session()->flash('submissionErrors', array('Deník se nepodařilo načíst.'));
                return redirect(route('submissionForm') . '#tm-section-2');
            }

            $validator = Validator::make($request->all(), [
                'diaryUrl' => 'required|max:255|unique:\App\Models\Diary,diary_url',
            ], $this->messages);
            if ($validator->fails()) {
                $request->session()->flash('submissionErrors', $validator->errors()->all());
                return redirect(route('submissionForm') . '#tm-section-2');
            }

            return redirect()->route('submissionForm', [ 'krok' => 2 ]);
        } elseif ($request->input('step') == 2) {
            $validator = Validator::make($request->all(), [
                'contest' => 'required|max:255',
                'category' => 'required|max:255',
                'diaryUrl' => 'required|max:255|unique:\App\Models\Diary,diary_url',
                'callSign' => 'required|max:255',
                'qthName' => 'required|max:255',
                'qthLocator' => ['required', 'size:6', new Locator],
                'qsoCount' => 'required|integer|gt:0',
                'email' => 'required|email',
            ], $this->messages);

            if ($validator->fails()) {
                $request->session()->flash('diary', [
                    'contest' => $request->input('contest'),
                    'category' => $request->input('category'),
                    'Url' => $request->input('diaryUrl'),
                    'callSign' => $request->input('callSign'),
                    'qthName' => $request->input('qthName'),
                    'qthLocator' => $request->input('qthLocator'),
                    'qsoCount' => $request->input('qsoCount'),
                    'email' => $request->input('email') ]);
                $request->session()->flash('submissionErrors', $validator->errors()->all());
                return redirect()->route('submissionForm', [ 'krok' => 2 ]);
            }

            try {
                $contestId = $this->contests->where('name', $request->input('contest'))->first()->id;
                $categoryId = $this->categories->where('name', $request->input('category'))->first()->id;

                $diary = new Diary;
                $diary->contest_id = $contestId;
                $diary->category_id = $categoryId;
                $diary->diary_url = $request->input('diaryUrl');
                $diary->call_sign = $request->input('callSign');
                $diary->qth_name = $request->input('qthName');
                $diary->qth_locator = $request->input('qthLocator');
                $diary->qso_count = $request->input('qsoCount');
                $diary->email = $request->input('email');
                $diary->save();

                $request->session()->flash('submissionSuccess', 'Hlášení do soutěže bylo úspěšně zpracováno.');
            } catch (\Exception $e) {
                $request->session()->flash('submissionErrors', array('Něco se pokazilo.'));
            }
            return redirect(route('submissionForm') . '#tm-section-2');
        } else {
            $request->session()->flash('submissionErrors', array('Něco se pokazilo.'));
            return redirect(route('submissionForm') . '#tm-section-2');
        }
    }
}
