@if($enabled)
    @auth
        <script src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-auto-logout', 'niladam/filament-auto-logout') }}"></script>
        <form id="auto-logout-form"
              data-auto-logout-enabled="{{ $enabled }}"
              action="{{ $logout_url }}"
              data-duration="{{ $duration }}"
              data-warn-before="{{ $warn_before }}"
              data-show-timeleft="{{ $show_time_left }}"
              data-time-left-text="{{ $time_left_text }}"
              data-notification-title="{{ $notification_title }}"
              data-notification-body="{{ $notification_body }}"
              data-units='@json($units, JSON_THROW_ON_ERROR)'
              method="POST" style="display: none;">
            @csrf
        </form>

        @if($show_time_left)
            <div id="idle-timeout-element"
                 class="
                idle-timeout-element hidden sm:flex items-center h-9 px-3 text-sm font-medium
                rounded-lg shadow-sm ring-1
                ring-custom-600/20 bg-custom-50 text-custom-600
                dark:ring-custom-400/30 dark:bg-custom-400/10 dark:text-custom-400
                text-center
        "
                 style="
                    --c-50: {{ $color[50] }};
                    --c-300: {{ $color[300] }};
                    --c-400: {{ $color[400] }};
                    --c-600: {{ $color[600] }};
                    "
            >
            </div>
        @endif
    @endauth
@endif
