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
            <x-filament::badge
                    id="idle-timeout-element"
                    class="idle-timeout-element [&>div]:px-1.5 [&>div]:py-1.5"
                    :color="$color"
                    :icon="$icon"
            />
        @endif
    @endauth
@endif
