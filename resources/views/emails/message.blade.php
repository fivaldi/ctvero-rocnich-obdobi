@component('mail::message')
Máte novou zprávu!
==================

**Od:** <{{ $from }}>

**Obsah zprávy:** {{ $message }}

--  
*Zpráva byla přijata přes kontaktní formulář aplikace {{ config('app.name') }}*
@endcomponent
