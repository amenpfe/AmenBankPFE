@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{-- config('app.name') --}}
            <img src="https://i.ibb.co/F3fzYHY/amen-logo.png?fbclid=IwAR00VFfAnhuSPGEdOzvlADVqAAV50sPOUlAWTgnrcHvRwRK5BBzMzZJXfYg" style="float:left; margin-left:1%;"
            width="12%"/>
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
