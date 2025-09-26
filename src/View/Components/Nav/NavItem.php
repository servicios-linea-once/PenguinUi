<?php

    namespace PenguinUi\View\Components\Nav;

    use Illuminate\Contracts\View\View;
    use Illuminate\View\Component;

    class NavItem extends Component
    {
        public string $uuid;

        public function __construct(
            public ?string $id = null,
            public ?string $label = null,
            public ?string $link = null,
            public ?bool   $noHover = false,
            public ?bool   $external = false,
            public ?bool   $noWireNavigate = false,
            // Slots
            public mixed  $actions = null,
        )
        {
            $this->uuid = 'penguin-' . md5(serialize($this)) . $id;
        }

        /**
         * @inheritDoc
         */
        public function render(): View|Closure|string
        {
            return <<<'blade'
                <li class="p-2 sm:p-2"  wire:key="{{ $uuid }}">
                <a
                @if($link)
                    href="{!! $link !!}"
                    @if($external)
                        target="_blank"
                    @endif

                    @if(!$external && !$noWireNavigate)
                        wire:navigate
                    @endif
                @endif
                @class([
                    'font-medium underline-offset-2 text-neutral-600 hover:text-primary hover:bg-on-surface-dark focus:outline-hidden focus:underline dark:text-white p-2
                    dark:hover:text-primary rounded-radius',
                ])
                 wire:current="!font-bold !text-blue-700"
                aria-current="page">{{$slot ?? $label}}</a>
               </li>
blade;

        }
    }