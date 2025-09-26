<?php

    namespace PenguinUi\View\Components\Nav;

    use Illuminate\View\Component;

    class Nav extends Component
    {
        public function __construct(
            public ?bool $sticky = false,
            public ?bool $fullWidth = false,

            // Slots
            public mixed $brand = null,
            public mixed $actions = null,
        ) {
            //
        }

        /**
         * @inheritDoc
         */
        public function render()
        {
            return <<<'HTML'
                    <nav  x-data="{ mobileMenuIsOpen: false }"
                    x-on:click.away="mobileMenuIsOpen = false"
                    {{ $attributes->class(["grid grid-flow-col justify-items-stretch justify-between w-full bg-surface-alt border-b border-outline dark:border-outline-dark px-6 py-4
                    dark:border-outline-dark dark:bg-surface-dark-alt", "sticky top-0 z-10" => $sticky]) }}>
                       <!-- Brand Logo -->
                       <div {{ $brand?->attributes->class(["text-2xl font-bold text-on-surface dark:text-on-surface-dark"]) }}>
                                {{ $brand }}
                       </div>

                        <!-- Menu Items (hidden on small screens) -->
                        <!-- Desktop Menu -->
                        <ul  {{ $actions?->attributes->class(["hidden items-center gap-2 shrink-0 sm:flex"]) }} id="desktopMenu">
                           {{ $actions }}
                        </ul>
                        <!-- Mobile Menu Button -->
                        <button x-on:click="mobileMenuIsOpen = !mobileMenuIsOpen" x-bind:aria-expanded="mobileMenuIsOpen" x-bind:class="mobileMenuIsOpen ? 'fixed top-6 right-6 z-20' : null" type="button" class="flex text-on-surface dark:text-on-surface-dark sm:hidden" aria-label="mobile menu" aria-controls="mobileMenu">
                            <svg x-cloak x-show="!mobileMenuIsOpen" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            <svg x-cloak x-show="mobileMenuIsOpen" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <!-- Mobile Menu -->
                        <ul x-cloak x-show="mobileMenuIsOpen"
                        x-transition:enter="transition motion-reduce:transition-none ease-out duration-300"
                        x-transition:enter-start="-translate-y-full"
                        x-transition:enter-end="translate-y-0"
                        x-transition:leave="transition motion-reduce:transition-none ease-out duration-300"
                        x-transition:leave-start="translate-y-0"
                        x-transition:leave-end="-translate-y-full"
                        {{ $actions?->attributes->class(["fixed max-h-svh overflow-y-auto inset-x-0 top-0 z-10 flex flex-col rounded-b-radius border-b border-outline bg-surface-alt px-8 pb-6 pt-10 dark:border-outline-dark dark:bg-surface-dark-alt sm:hidden"]) }}
                        id="mobileMenu">
                            {{ $actions }}
                        </ul>
                    </nav>
                HTML;
        }
    }