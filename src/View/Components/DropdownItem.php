<?php

namespace PenguinUi\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class DropdownItem extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $icon = null,
        public ?string $spinner = null,
        public ?string $link = null,
        public ?string $route = null,
        public ?bool $external = false,
        public ?bool $noWireNavigate = false,
        public ?string $badge = null,
        public ?string $badgeClasses = null,
        public ?bool $active = false,
        public ?bool $separator = false,
        public ?bool $hidden = false,
        public ?bool $disabled = false,
        public ?bool $exact = false
    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
    }

    public function spinnerTarget(): ?string
    {
        if ($this->spinner == 1) {
            return $this->attributes->whereStartsWith('wire:click')->first();
        }

        return $this->spinner;
    }

    public function routeMatches(): bool
    {
        if ($this->link == null) {
            return false;
        }

        if ($this->route) {
            return request()->routeIs($this->route);
        }

        $link = url($this->link ?? '');
        $route = url(request()->url());

        if ($link == $route) {
            return true;
        }

        return ! $this->exact && $this->link !== '/' && Str::startsWith($route, $link);
    }
    /**
     * @inheritDoc
     */
    public function render()
    {
        if ($this->hidden === true) {
            return '';
        }

        return<<<'blade'
            @aware(['activateByRoute' => false, 'activeBgColor' => 'bg-base-300'])
            <a @if($link)
                href="{{ $link }}"

                @if($external)
                    target="_blank"
                @endif

                @if(!$external && !$noWireNavigate)
                    {{ $attributes->wire('navigate')->value() ? $attributes->wire('navigate') : 'wire:navigate' }}
                @endif
            @endif 
            @class(["flex items-center gap-2 bg-surface-alt px-4 py-2 text-sm text-on-surface hover:bg-surface-dark-alt/5 hover:text-on-surface-strong focus-visible:bg-surface-dark-alt/10 focus-visible:text-on-surface-strong focus-visible:outline-hidden dark:bg-surface-dark-alt dark:text-on-surface-dark dark:hover:bg-surface-alt/5 dark:hover:text-on-surface-dark-strong dark:focus-visible:bg-surface-alt/10 dark:focus-visible:text-on-surface-dark-strong","bg-surface-dark-alt/5 " => ($active || ($activateByRoute && $routeMatches()))]) role="menuitem">
            @if($icon)
                <span @class([$icon => ! $spinner, 'icon-[eos-icons--bubble-loading]' => $spinner , ' size-5']) @if($spinner) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif></span>
            @endif
            {{ $title }}
            </a>
blade;

    }
}