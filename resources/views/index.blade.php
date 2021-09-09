@extends('layouts.app')

@section('title', 'Home')

@section('sections')

@include('recaptcha', [ 'formId' => 'contact-form' ])

    <section class="tm-section-2 my-5 py-4">
        <div class="row">
            <div class="col-xl-20 col-lg-20 col-md-12">
                <header>
                <h2>Novinky a oznámení</h2>
                </header>
                {{ Illuminate\Mail\Markdown::parse(Illuminate\Support\Facades\Storage::get('content/news.md')) }}
            </div>
        </div>
    </section>

    <section class="tm-section-3 tm-section-mb" id="tm-section-3">
        <div class="row">
            <div class="col-md-6 tm-mb-sm-4 tm-2col-l">
                <div class="h-100 tm-box-3">
                    <header>
                    <h3>Termíny</h3>
                    </header>
                    <p class="small">obvykle sobota 0:00 až neděle 24:00</p>
                    @foreach ($lastYearContests as $contest)
                        <dl class="mt-4 contests">
                            <dt>{{ $contest->name }}</dt>
                            <dd>{{ date('j.n.Y H:i', strtotime($contest->contest_start)) }} — {{ date('j.n.Y H:i', strtotime($contest->contest_end)) }}<a class="ml-2" href="{{ route('calendar', [ 'soutez' => $contest->name ]) }}"><i class="fa fa-calendar"></i></a></dd>
                        </dl>
                    @endforeach

                    <header>
                    <h3 class="mt-5">Spojení</h3>
                    </header>
                    <p>Každé spojení by mělo obsahovat: volačku (volací znak nebo jméno), QTH - místo odkud vysílá, <a href="http://www.cbpmr.cz/lokatorova-mapa-gps.html">lokátor</a>, report.</p>
                    <p>Za každé spojení je 1 bod, pokud protistanice změní polohu a přesune se do jiného lokátoru (dále než je vedlejší) je i takové spojení platné. Můžete si připsat i spojení přes opakovač (1 bod za všechna spojení přes jeden opakovač). V případě vysílání z více stanovišť zapište do hlášení to "nejlepší" QTH a všechna spojení sečtěte.</p>

                    <header>
                    <h3 class="mt-5">Deník</h3>
                    </header>
                    <p>Deník je možné psát v libovolném formátu. Ve spolupráci s <a href="https://www.cbpmr.info/">cbpmr.info</a> nabízíme možnost těm, kteří ještě nejsou plnohodnotnými uživateli <a href="https://www.cbpmr.info/login">LOGBOOKu</a>, po dobu těchto termínů plnou verzi zapisovače spojení (o tuto možnost je nutno předem zažádat emailem na soutez(a)svysilackou.cz alespoň 24h předem ). Také není podmínkou deník zveřejňovat v bance deníků cbpmr.cz/deniky, ale doporučuje se pro transparentnost a pro účely vyhodnocení vkládat číslo deníku právě z této stránky.</p>
                    <p>Pokud tento deník používáte i do jiné soutěže, prosím o zaslání na email níže (v době, kdy už bude možné ho zveřejnit), aby mohl být doplněn do tabulky výsledků.</p>
                </div>
            </div>
            <div class="col-md-6 tm-2col-r">
                <div class="h-100 tm-box-3">
                    <header>
                    <h3>Kategorie</h3>
                    </header>
                    <p>Vzhledem k možnostem CB od pendreku přes protiváhu, drátovku ..... až po směrovku na stožáru je velice složité striktně upřesnit kategorie. Vyberte si kategorii na kterou se nejlépe cítíte.</p>
                    <p><b>Pěšák</b> - CB ručka (tak říkajíc vybavení co se vejde do kapsy, drátové antény atd., doprava na kopec převážně vlastními silami)</p>
                    <p><b>Mobil</b> - vysílá z auta na mobilní anténu na autě (k tomu snad není co dodat)</p>
                    <p><b>Obrněnec</b> - stožár s anténou (pevné konstrukce, doprava hromadná, auto, lanovka, elektropohon ...)</p>
                    <p><b>Domased</b> - vysílá z domácího QTH</p>
                    <p><b>Sdílenky</b> - SDÍLENÉ KMITOČTY podle všeobecného oprávnění</p>

                    <header>
                    <h3 class="mt-5">Technika</h3>
                    </header>
                    <p>Vysílací zařízení musí splňovat legislativní podmínky státu z jehož území je vysíláno.</p>

                    <header>
                    <h3 class="mt-5">Hodnocení</h3>
                    </header>
                    <p>Hodnocení proběhne automaticky po vyplnění formuláře <a href="{{ route('submissionForm') }}">ZDE</a>. Každé období se bude vyhodnocovat samostatně. Zápis je vhodné udělat co nejdříve avšak maximálně do 30 dnů od termínu vysílání.</p>
                    <p>Na konci každého období budou všechny údaje z formulářů smazány. Kdo bude chtít může poslat pár slov popřípadě nějakou fotečku. Bude použito pro vytvoření článku o proběhlé akci a vystaveno na tomto webu a sdíleno i na jiných s tématikou CB. V případě zájmu odešlete na email soutez(a)svysilackou.cz.</p>

                    <p class="mt-5"><b>Všem přeji dobré podmínky, málo rušení a mnoho dalekých spojení. 73'</b></p>
                </div>
            </div>
        </div>
    </section>

    <section class="tm-section-12 tm-section-mb">
            <div class="col-lg-12 col-md-12 col-sm-12 pl-lg-0">
                <header>
                <h2 class="mb-4">Nejlepší trojka</h2>
                </header>
                @foreach ($categories as $category)
                <div class="media tm-media">
                    <img src="{{ $category['image_src'] }}" class="img-responsive tm-media-img">
                    <div class="media-body tm-box-5">
                        <h3>{{ $category['name'] }}</h3>

                        <table class="small text-left" style="width: 100%">
                            <tr style="background-color: silver">
                                <th class="col-1 d-none d-md-table-cell">Datum</th>
                                <th class="col-3">Volačka</th>
                                <th class="col-3">QTH</th>
                                <th class="col-1 d-none d-md-table-cell">Lokátor</th>
                                <th class="col-1">Deník</th>
                                <th class="col-2">Počet QSO</th>
                                @if ($useScorePoints)
                                <th class="col-1">Body</th>
                                @endif
                            </tr>
                            @foreach (array_slice($lastContestDiaries[$category['id']] ?? [], 0, 3) as $diary)
                                <tr><td class="col-1 d-none d-md-table-cell">{{ date('j.n.Y', strtotime($diary['created_at'])) }}</td>
                                    <td class="col-3">{{ $diary['call_sign'] }}</td>
                                    <td class="col-3">{{ $diary['qth_name'] }}</td>
                                    <td class="col-1 d-none d-md-table-cell">{{ $diary['qth_locator'] }}</td>
                                    <td class="col-1"><a href="{{ $diary['diary_url'] }}" target="_blank"><i class="fa fa-book fa-lg"></i></a></td>
                                    <td class="col-2">{{ $diary['qso_count'] }}</td>
                                    @if ($useScorePoints)
                                    <td class="col-1">{{ $diary['score_points'] }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
                @endforeach
            </div>
    </section>

    <section class="tm-section-4 tm-section-mb my-3" id="tm-section-contact">
        <div class="row">
        </div>
    </section>

    <section class="tm-section-6 mt-3" id="tm-section-6">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-xs-12">
                <div class="contact_message" id="contact-message">
                    <h2 class="mb-4">Kontaktní formulář</h2>
                    @if ($messageSuccess = request()->session()->pull('messageSuccess'))
                        <div class="alert alert-success">
                            {{ $messageSuccess }}
                        </div>
                        <script>location.hash = '#contact-message';</script>
                    @elseif ($messageErrors = request()->session()->pull('messageErrors'))
                        <div class="alert alert-danger">
                            @if (count($messageErrors) == 1)
                                {{ $messageErrors[0] }}
                            @else
                                <ul>
                                @foreach ($messageErrors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            @endif
                        </div>
                        <script>location.hash = '#contact-message';</script>
                    @endif
                    <form action="message" method="post" class="contact-form" id='contact-form'>
                        <div class="form-group row align-items-center col-12">
                            <label for="email" class="col-2">E-mail</label>
                            <input name="email" type="email" class="form-control col-10" id="email" placeholder="name@example.com">
                        </div>
                        <div class="form-group row align-items-center col-12">
                            <label for="subject" class="col-2">Předmět</label>
                            <input name="subject" type="text" class="form-control col-10" id="subject" placeholder="Předmět zprávy">
                        </div>
                        <div class="form-group row col-12">
                            <label for="message">Zpráva</label>
                            <textarea name="message" class="form-control" id="message" placeholder="Text zprávy" rows="6"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary g-recaptcha" data-sitekey="{{ config('ctvero.recaptchaSiteKey') }}" data-callback="onSubmit" data-action="submit">Odeslat</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5 col-md-5 col-xs-12 tm-contact-right">
                <div class="tm-address-box">
                    <h2 class="mb-4">Kontakt</h2>
                    <div class="mb-4">
                        <p>V případě dotazů, návrhů či připomínek mne neváhejte kontaktovat.</p>
                        <p class="text-right pr-5">73 Radek Lichnov</p>
                    </div>
                    <div class="mb-4">
                        <p class="mb-0">Diskuze k souteži na stránce</p>
                        <a href="https://forum.svysilackou.cz/showthread.php?tid=112"><img alt="forum.svysilackou.cz" src="https://forum.svysilackou.cz/images/simplicity/logo_n.png"></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
