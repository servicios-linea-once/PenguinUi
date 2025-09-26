<?php

    namespace PenguinUi\View\Components\menu;

    use Illuminate\Contracts\View\View;
    use Illuminate\View\Component;

    class Menu extends Component
    {

        public string $uuid;

        public function __construct(
            public ?string $id = null,
            public ?string $title = null,
            public ?string $icon = null,
            public ?bool   $separator = false,
            public ?bool   $activateByRoute = false,
            public ?string $activeBgColor = 'bg-primary/20',
        )
        {
            $this->uuid = 'penguin-' . md5(serialize($this)) . $id;
        }

        /**
         * @inheritDoc
         */
        public function render(): View|Closure|string
        {
            return <<<'BLADE'
                <ul {{ $attributes->class(["w-full"]) }} >
                    @if($title)
                        <a {{ $attributes->twMergeFor('title', 'flex items-center
                        gap-2 px-2 py-1.5 text-sm rounded-radius font-medium text-on-surface underline-offset-2 hover:bg-primary/5
                        hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong text-inherit uppercase') }}>
                            <span class="flex items-center gap-2">

                                @if($icon)
                                  <span @class([$icon,'size-4'])></span>
                                @endif

                                {{ $title }}
                            </span>
                        </a>
                    @endif

                    @if($separator)
                        <hr class="mb-3 border-t-[length:var(--border)] border-base-content/10" />
                    @endif

                    {{ $slot }}
                </ul>
            BLADE;
        }
    }