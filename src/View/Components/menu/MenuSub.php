<?php

    namespace PenguinUi\View\Components\menu;

    use Illuminate\View\Component;

    class MenuSub extends Component
    {
        public string $uuid;

        public function __construct(
            public ?string $id = null,
            public ?string $title = null,
            public ?string $icon = null,
            public bool    $open = false,
            public ?bool   $hidden = false,
            public ?bool   $disabled = false,
        )
        {
            $this->uuid = 'penguin-' . md5(serialize($this)) . $id;
        }

        /**
         * @inheritDoc
         */
        public function render()
        {
            return <<<'blade'
            <!-- collapsible item  -->
            <div x-data="{ isExpanded:  @if($open) true @else false @endif }" class="flex flex-col">
                <button type="button" x-on:click="isExpanded = ! isExpanded" id="{{$uuid}}-btn" aria-controls="user-management" x-bind:aria-expanded="isExpanded ? 'true' : 'false'"
                    {{ $attributes->twMergeFor('button','flex items-center justify-between rounded-radius gap-2 px-3 py-2.5 cursor-pointer text-sm font-medium underline-offset-2
                    focus:outline-hidden
                focus-visible:underline')}} x-bind:class="isExpanded ? 'text-on-surface-strong bg-primary/10 dark:text-on-surface-dark-strong dark:bg-primary-dark/10' :  'text-on-surface hover:bg-primary/5 hover:text-on-surface-strong dark:text-on-surface-dark dark:hover:text-on-surface-dark-strong dark:hover:bg-primary-dark/5'">
                    @if($icon)
                        <span @class([$icon,'size-5 shrink-0'])></span>)
                    @endif
                    <span class="mr-auto text-left whitespace-nowrap truncate">{{ $title }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 transition-transform rotate-0 shrink-0" x-bind:class="isExpanded ? 'rotate-180' : 'rotate-0'" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </button>

                <ul x-cloak x-collapse x-show="isExpanded" aria-labelledby="user-management-btn" id="{{$uuid}}" class="mt-1 space-y-1 ml-3 pl-3 border-l border-on-surface/10
                dark:border-on-surface-dark/10">
                    {{ $slot }}
                </ul>
            </div>
blade;

        }
    }