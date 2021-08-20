@extends('layouts.app')

@section('title', 'Home')

@section('sections')

    <section class="tm-section-2 tm-section-mb">
        <div class="row">
            <div class="col-xl-20 col-lg-20 col-md-12">
                <br>
                <p><b>Děkuji všem za účast a budu se těšit na slyšenou v dalším kole.</b></p>
                <br>
                <p>Pokud by se našel webmaster který by mi ve svém volném čase pomohl upravit některé funkce budu rád za pomoc. Já jsem jen samouk a podle toho to taky vypadá :-)</p>
                <br>
                <p><b>NOVINKA: Vzhledem k DX podmínkám na CB které moc nepřejí lokálním spojením jsem se rozhodl přidat kategorii pro sdílené kmitočty. Povede se udělat nějaká spojení ?</b></p>
                <br>

            </div>
        </div>
    </section>

    <section class="tm-section-3 tm-section-mb" id="tm-section-3">
        <div class="row">
            <div class="col-md-6 tm-mb-sm-4 tm-2col-l">
                <div class="tm-box-3">
                    <header>
                    <h3>Termíny</h3>
                    </header>
                    <p>od soboty 0:00 do neděle 24:00</p>
                    @foreach ($lastYearContests as $contest)
                        <p><b>{{ $contest->name }}</b>  . . . {{ date('j.n.Y H:i', strtotime($contest->contest_start)) }} — {{ date('j.n.Y H:i', strtotime($contest->contest_end)) }} </p>
                    @endforeach
                    <br>

                    <header>
                    <h3>Spojení</h3>
                    </header>
                    <p>Každé spojení by mělo obsahovat: volačku (volací znak nebo jméno), QTH - místo odkud vysílá, <a href="http://www.cbpmr.cz/lokatorova-mapa-gps.html">lokátor</a>, report.</p>
                    <p>Za každé spojení je 1 bod, pokud protistanice změní polohu a přesune se do jiného lokátoru (dále než je vedlejší) je i takové spojení platné. Můžete si připsat i spojení přes opakovač (1 bod za všechna spojení přes jeden opakovač). V případě vysílání z více stanovišť zapište do hlášení to "nejlepší" QTH a všechna spojení sečtěte.</p>
                    <br>

                    <header>
                    <h3>Deník</h3>
                    </header>
                    <p>Deník je možné psát v libovolném formátu. Ve spolupráci s <a href="https://www.cbpmr.info/">cbpmr.info</a> nabízíme možnost těm, kteří ještě nejsou plnohodnotnými uživateli <a href="https://www.cbpmr.info/login">LOGBOOKu</a>, po dobu těchto termínů plnou verzi zapisovače spojení (o tuto možnost je nutno předem zažádat emailem na soutez(a)svysilackou.cz alespoň 24h předem ). Také není podmínkou deník zveřejňovat v bance deníků cbpmr.cz/deniky, ale doporučuje se pro transparentnost a pro účely vyhodnocení vkládat číslo deníku právě z této stránky.</p>
                    <p>Pokud tento deník používáte i do jiné soutěže, prosím o zaslání na email níže (v době, kdy už bude možné ho zveřejnit), aby mohl být doplněn do tabulky výsledků.</p>
                    <p></p>
                    <br>
                    <br>


                </div>
            </div>
            <div class="col-md-6 tm-2col-r">
                <div class="tm-box-3">
                    <header>
                    <h3>Kategorie</h3>
                    </header>
                    <p>Vzhledem k možnostem CB od pendreku přes protiváhu, drátovku, ...... až po směrovku na stožáru je velice složité striktně upřesnit kategorie. Vyberte si kategorii na kterou se nejlépe cítíte.</p>
                    <p><b>Pěšák</b> - CB ručka (tak říkajíc vybavení co se vejde do kapsy, drátové antény atd., doprava na kopec převážně vlastními silami))</p>
                    <p><b>Mobil</b> - vysílá z auta na mobilní anténu na autě (k tomu snad není co dodat)</p>
                    <p><b>Obrněnec</b> - stožár s anténou (pevné konstrukce, doprava hromadná, auto, lanovka, elektropohon,...)</p>
                    <p><b>Domased</b> - vysílá z domácího QTH</p>
                    <p><b>Sdílenky</b> - SDÍLENÉ KMITOČTY podle všeobecného oprávnění</p>
                    <br>

                    <header>
                    <h3>Technika</h3>
                    </header>
                    <p>Vysílací zařízení musí splňovat legislativní podmínky státu z jehož území je vysíláno.</p>
                    <br>

                    <header>
                    <h3>Hodnocení</h3>
                    </header>
                    <p>Hodnocení proběhne automaticky po vyplnění formuláře <a href="https://forms.gle/F67fo1gW83B73zhs9" target="_blank">ZDE</a> Každé období se bude vyhodnocovat samostatně. Zápis je vhodné udělat co nejdříve avšak maximálně do 30 dnů od termínu vysílání. </p>
                    <p>Na konci každého období budou všechny údaje z formulářů smazány. Kdo bude chtít může poslat pár slov popřípadě nějakou fotečku. Bude použito pro vytvoření článku o proběhlé akci a vystaveno na tomto webu a sdíleno i na jiných s tématikou CB. V případě zájmu odešlete na email soutez(a)svysilackou.cz. </p>
                    <br>
                    <p><b>Všem přeji dobré podmínky, málo rušení a mnoho dalekých spojení. 73'</b></p>
                </div>
            </div>
        </div>
    </section>

    <section class="tm-section-2 tm-section-mb">
        <div class="row">
            <div class="col-xl-20 col-lg-20 col-md-12">

                    <header>
                    <h2>Upozornění</h2>
                    </header>
                    <p>Buďte všichni v této prapodivné době a také v průběhu této aktivity zodpovědní, chovejte se tak, aby jste neohrozili sebe nebo své okolí.<b> V případě portejblů a vysílání mimo domov dodržujte doporučení a nařízení vlády.</b></p>

            </div>
        </div>
    </section>

    <section class="tm-section-12 tm-section-mb">
            <div class="col-lg-12 col-md-12 col-sm-12 pl-lg-0">
                    <header>
                    <h2>Nejlepší trojka<br><br></h2>
                    </header>
                @foreach ($categories as $category)
                <div class="media tm-media">
                    <img src="{{ $category['image_src'] }}" class="img-responsive tm-media-img">
                    <div class="media-body tm-box-5">
                        <h2>{{ $category['name'] }}</h2>

                        <font size="2" >
                        <table style="text-align:left; width:100%">
                            <tr><th width="50">Datum</th>
                                <th width="100">Volačka</th>
                                <th width="100">QTH</th>
                                <th width="50">Lokátor</th>
                                <th width="50">Deník</th>
                                <th width="50">Počet QSO</th>
                                @if ($useScorePoints)
                                <th width="50">Body</th></tr>
                                @endif
                            @foreach (array_slice($lastContestDiaries[$category['id']], 0, 3) as $diary)
                                <tr><td>{{ date('j.n.Y', strtotime($diary['created_at'])) }}</td>
                                    <td>{{ $diary['call_sign'] }}</td>
                                    <td>{{ $diary['qth_name'] }}</td>
                                    <td>{{ $diary['qth_locator'] }}</td>
                                    <td><a href="{{ $diary['diary_url'] }}" target="_blank">deník</a></td>
                                    <td>{{ $diary['qso_count'] }}</td>
                                    @if ($useScorePoints)
                                    <td>{{ $diary['score_points'] }}</td></tr>
                                    @endif
                            @endforeach
                        </table>
                        </font>

                    </div>
                </div>
                @endforeach
            </div>
    </section>

    <br>
    <br>
    <br>
    <br>

    <section class="tm-section-4 tm-section-mb" id="tm-section-contact">
        <div class="row">
        </div>
    </section>

    <section class="tm-section-6" id="tm-section-6">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-xs-12">
                <div class="contact_message">
                    @if (isset($mailSuccess))
                        <div class="alert alert-success">
                            {{ $mailSuccess }}
                        </div>
                    @elseif (isset($mailErrors))
                        <div class="alert alert-danger">
                            @if (count($mailErrors) == 1)
                                {{ $mailErrors[0] }}
                            @else
                                <ul>
                                @foreach ($mailErrors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif
                    <form action="message" method="post" class="contact-form">
                        <div class="row mb-2">
                            <div class="form-group col-xl-6">
                                <div class="form-group col-xl-6">
                                    E-mail<input name="email" size="25" value=""><br>
                                </div>
                                <div class="form-group col-xl-6">
                                    Předmět<input name="subject" size="25" value=""><br>
                                </div>
                                <div class="form-group col-xl-6">
                                    1 + 1 = <input name="spamCheck" size="10" value=""><br>
                                </div>
                                <div class="form-group col-xl-6">
                                    Zpráva<textarea name="message" rows="6" cols="55"></textarea><br>
                                </div>
                                <div class="form-group col-xl-6">
                                    <input type="submit" value="Odeslat">
                                </div>
                            </div>
                       </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5 col-md-5 col-xs-12 tm-contact-right">
                <div class="tm-address-box">
                    <h2 class="mb-4">Kontakt</h2>
                    <p class="mb-5">V případě dotazů, návrhů či připomínek mne neváhejte kontaktovat.<br>&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp;73 Radek Lichnov</p>
                    <p class="mb-5">Diskuze k souteži na stránce <br><a href="https://forum.svysilackou.cz/showthread.php?tid=112"><img alt="forum.svysilackou.cz" src="https://forum.svysilackou.cz/images/simplicity/logo_n.png"></a></p>

                </div>
            </div>
        </div>
    </section>

@endsection
