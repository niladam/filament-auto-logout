<?php

namespace Niladam\FilamentAutoLogout;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\View;
use Niladam\FilamentAutoLogout\Enums\AutoLogoutPosition;

class FilamentAutoLogoutPlugin implements Plugin
{
    use EvaluatesClosures;

    public bool | Closure $enabled = true;

    public bool | Closure $hasWarning = true;

    public bool | Closure $showTimeLeft = true;

    public int | Closure $duration = 900;

    public int | Closure $warnBeforeSeconds = 30;

    public AutoLogoutPosition | Closure $position = AutoLogoutPosition::BOTTOM_RIGHT;
    public ?string $timeleftText = null;

    public function getId(): string
    {
        return 'filament-auto-logout';
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(
            PanelsRenderHook::BODY_END,
            fn () => $this->renderView()
        );
    }

    public function boot(Panel $panel): void
    {
        $this->timeleftText = $this->timeleftText ?? config('filament-auto-logout.time_left_text');

    }

    protected function renderView(): string
    {
        return View::make('filament-auto-logout::main', [
            'enabled' => $this->evaluate($this->enabled),
            'has_warning' => $this->evaluate($this->hasWarning),
            'show_time_left' => $this->evaluate($this->showTimeLeft),
            'duration' => $this->evaluate($this->duration),
            'warn_before' => $this->evaluate($this->hasWarning) ? $this->evaluate($this->warnBeforeSeconds) : 0,
            'position' => $this->position->getPosition(),
            'time_left_text' => $this->timeleftText,
        ])->render();
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function enableIf(bool | Closure $enabled = true): static
    {
        $this->enabled = $enabled instanceof Closure
            ? (bool) $this->evaluate($enabled)
            : $enabled;

        return $this;
    }

    public function disableIf(Closure $callback): static
    {
        return $this->enableif((bool) $this->evaluate($callback));
    }

    public function withoutWarning(): static
    {
        $this->hasWarning = false;

        return $this;
    }

    public function withoutTimeLeft(): static
    {
        $this->showTimeLeft = false;

        return $this;
    }

    public function warnBefore(int | Closure $warnBefore): static
    {
        $this->warnBeforeSeconds = $warnBefore instanceof Closure
            ? (int) $this->evaluate($warnBefore)
            : $warnBefore;

        return $this;
    }

    public function positionLeft(AutoLogoutPosition $position = AutoLogoutPosition::BOTTOM_LEFT): static
    {
        $this->position = $position;

        return $this;
    }

    public function logoutAfter(int | Closure $duration): static
    {
        $this->duration = $duration instanceof Closure
            ? (int) $this->evaluate($duration)
            : $duration;

        return $this;
    }

    public function timeLeftText(?string $timeLeftText): static
    {
        $this->timeleftText = $timeLeftText;

        return $this;
    }
}
