<?php

    namespace PenguinUi\View\Components;

    use Illuminate\Contracts\View\View;
    use Illuminate\View\Component;

    class Model extends Component
    {
        public function __construct(
            public ?string $title = null,
            public ?bool $persistent = false,
            public ?bool $withoutTrapFocus = false,
            public ?string $position = 'center',

            // Slots
            public ?string $actions = null
        ) {
            //
        }

        public function positionClassSet()
        {
            return match ($this->position) {
                'top' => 'items-start pt-8 justify-center',
                'top-left' => 'items-start pt-8 justify-start',
                'top-right' => 'items-start pt-8 justify-end',
                'center-left' => 'items-center justify-start',
                'center-right' => 'items-center justify-end',
                'bottom-left' => 'items-end pb-8 justify-start',
                'bottom-right' => 'items-end pb-8 justify-end',
                'bottom' => 'items-end justify-center pb-8',
                default => 'items-center justify-center',
            };
        }

        /**
         * @inheritDoc
         */
        public function render(): View|Closure|string
        {
            return <<<'blade'
    <div x-data="{modalIsOpen:  @entangle($attributes->wire('model')).live}">

        <div x-cloak x-show="modalIsOpen"
        x-transition.opacity.duration.200ms
        x-trap.inert.noscroll="modalIsOpen"
        @if(!$persistent)
            @keydown.escape.window = "$wire.{{ $attributes->wire('model')->value() }} = false"
        @endif
        x-on:click.self="modalIsOpen = false"
         @if(!$withoutTrapFocus)
            x-trap="modalIsOpen" x-bind:inert="!modalIsOpen"
        @endif
        class="fixed inset-0 z-30 flex w-full bg-black/20 p-4 pb-8 backdrop-blur-md lg:p-8"
        :class="{'{{ $positionClassSet() }}': modalIsOpen }"
        role="dialog"
        aria-modal="true"
        aria-labelledby="defaultModalTitle">
            <!-- Modal Dialog -->
            <div x-show="modalIsOpen"
                x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
                x-transition:enter-start="opacity-0 scale-y-0"
                x-transition:enter-end="opacity-100 scale-y-100"
             class="flex min-w-sm max-w-lg flex-col gap-4 overflow-hidden rounded-radius bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt
             dark:text-on-surface-dark">
                <!-- Dialog Header -->
                <div class="grid grid-flow-col justify-items-stretch items-center border-b border-outline/30 bg-surface-alt/60 p-2 dark:border-outline-dark
                dark:bg-surface-dark/20">
                    @if($title)
                        <h3 id="defaultModalTitle" class="font-semibold tracking-wide text-on-surface-strong dark:text-on-surface-dark-strong">{{$title}}</h3>
                    @endif
                    <button x-on:click="modalIsOpen = false" aria-label="close modal" class="cursor-pointer rounded-radius p-1 justify-self-end">
                        <span class="icon-[line-md--close-circle] size-6"></span>
                    </button>
                </div>
                <!-- Dialog Body -->
                <div class="px-4 py-8">
                    {{$slot}}
                </div>
                <!-- Dialog Footer -->
                @if($actions)
                    <div class="flex flex-col-reverse justify-between gap-2 border-t border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20 sm:flex-row sm:items-center md:justify-end">
                        {{$actions}}
                    </div>
                @endif
            </div>
        </div>
    </div>
blade;
        }
    }