<?php

    namespace PenguinUi\View\Components\menu;

    use Illuminate\View\Component;

    class MenuSeparador extends Component
    {
        public string $uuid;

        /**
         * Create a new component instance.
         *
         * @return void
         * @params $id
         */
        public function __construct(public ?string $id = null,
                                    public ?string $title = null,
                                    public ?string $icon = null,
        )
        {
            $this->uuid = 'penguin-' . md5(serialize($this)) . $id;
            //
        }

        /**
         * Get the view / contents that represent the component.
         *
         * @return \Illuminate\Contracts\View\View|Closure|string
         */
        public function render(): \Illuminate\Contracts\View\View|Closure|string
        {
            return <<<'BLADE'
                <hr class="my-3 border-t-[1px] border-base-content/10"/>

                @if($title)
                    <li {{ $attributes->twMergeFor('title', 'flex items-center
                        gap-2 px-2 py-1.5 text-sm rounded-radius font-medium text-on-surface underline-offset-2 hover:bg-primary/5
                        hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong text-inherit uppercase') }}>
                        <div class="flex items-center gap-2">
                            @if($icon)
                              <span @class([$icon,'size-4'])></span>
                            @endif
                            {{ $title }}
                        </div>
                    </li>
                @endif
            BLADE;
        }

    }