@auth
    <script src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-auto-logout', 'niladam/filament-auto-logout') }}"></script>
    <form id="auto-logout-form"
          data-auto-logout-enabled="{{ $enabled }}"
          action="{{ route($route_name) }}"
          data-duration="{{ $duration }}"
          data-warn-before="{{ $warn_before }}"
          data-show-timeleft="{{ $show_time_left }}"
          data-time-left-text="{{ $time_left_text }}"
          method="POST" style="display: none;">
        @csrf
    </form>
    
    <style>
        #idle-timeout-element {
            position: fixed;
            bottom: 50px;
            padding: 6px;
            background: #333;
            color: #fff;
            z-index: 20;
            {{ $position }}: 10px;
            border-radius: 5px;
        }
    </style>
@endauth
