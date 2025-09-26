<?php

    namespace PenguinUi\View\Components\menu;

    use Closure;
    use Illuminate\Contracts\View\View;
    use Illuminate\Support\Str;
    use Illuminate\View\Component;

    class MenuItem extends Component
    {

        public string $uuid;

        public function __construct(
            public ?string $id = null,
            public ?string $icon = null,
            public ?string $spinner = null,
            public ?string $link = null,
            public ?string $route = null,
            public ?bool   $external = false,
            public ?bool   $noWireNavigate = false,
            public ?bool   $separator = false,
            public ?bool   $hidden = false,
            public ?bool   $disabled = false,
            public ?bool   $exact = false,
            public mixed $title = null,
        )
        {
            $this->uuid = 'penguin-' . md5(serialize($this)) . $id;
        }

        public function spinnerTarget(): ?string
        {
            if ($this->spinner === 1) {
                return $this->attributes->whereStartsWith('wire:click')->first();
            }

            return $this->spinner;
        }

        public function routeMatches(): bool
        {
            if ($this->link === null) {
                return false;
            }

            if ($this->route) {
                return request()->routeIs($this->route);
            }

            $link = url($this->link);
            $route = url(request()->url());

            if ($link === $route) {
                return true;
            }

            return ! $this->exact && $this->link !== '/' && Str::startsWith($route, $link);
        }
        /**
         * @inheritDoc
         */
        public function render(): View|Closure|string
        {
            if ($this->hidden === true) {
                return '';
            }
            return <<<'BLADE'
                @aware(['activateByRoute' => false, 'activeBgColor' => 'bg-base-300'])
                <li>
                    <a  @if($link)
                            href="{{ $link }}"

                            @if($external)
                                target="_blank"
                            @endif

                            @if(!$external && !$noWireNavigate)
                                {{ $attributes->wire('navigate')->value() ? $attributes->wire('navigate') : 'wire:navigate' }}
                            @endif
                        @endif
                        @if($spinner)
                            wire:target="{{ $spinnerTarget() }}"
                            wire:loading.attr="disabled"
                        @endif
                         {{ $attributes->twMergeFor('title', 'flex items-center rounded-radius gap-2 px-3 py-2.5 text-sm font-medium text-on-surface underline-offset-2
                         bg-gray-200/50 dark:bg-gray-700/50 my-1 hover:text-inherit
                         hover:bg-primary/20 hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5
                         dark:hover:text-on-surface-dark-strong cursor-pointer') }}  wire:current="font-bold bg-primary/20">

                        {{-- SPINNER --}}
                        @if($spinner)
                            <span wire:loading wire:target="{{ $spinnerTarget() }}" class="icon-[eos-icons--loading] w-5 h-5"></span>
                        @endif

                        @if($icon)
                            <span class="block py-0.5 self-center flex " @if($spinner) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif>
                                <span @class(['mb-0.5 size-5',$icon])></span>
                            </span>
                        @endif
                        <span class=" whitespace-nowrap font-bold truncate">
                            @if($title && is_string($title))
                                {{ __($title) }}
                            @else
                                {{ $slot }}
                            @endif
                        </span>
                    </a
                </li>
 BLADE;
        }
    }