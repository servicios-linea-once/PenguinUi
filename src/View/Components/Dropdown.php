<?php

namespace PenguinUi\View\Components;

use Illuminate\View\Component;

class Dropdown extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $icon = 'icon-[flowbite--caret-down-solid]',
        public ?string $position = 'botton',
        // Slots
        public mixed $trigger = null
    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
    }
    /**
     * @inheritDoc
     */
    public function render()
    {
        return <<<'blade'
            <div x-data="{ isOpen: false, openedWithKeyboard: false }" 
                class="relative w-fit" 
                @click.outside="isOpen = false"
                x-on:keydown.esc.window="isOpen = false, openedWithKeyboard = false">
                <!-- Toggle Button Custom -->
                @if($trigger)
                    <div x-ref="button" @click.prevent="isOpen = !isOpen" {{ $trigger->attributes->class(['list-none']) }}>
                        {{ $trigger }}
                    </div>
                @else
                    <!-- DEFAULT TRIGGER -->
                     <button type="button" 
                      @class(['inline-flex items-center gap-2 whitespace-nowrap rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-outline-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:focus-visible:outline-outline-dark-strong'])
                      x-ref="button" x-on:click="isOpen = ! isOpen" {{ $attributes->merge() }} aria-haspopup="true" x-on:keydown.space.prevent="openedWithKeyboard = true" x-on:keydown.enter.prevent="openedWithKeyboard = true" x-on:keydown.down.prevent="openedWithKeyboard = true" x-bind:class="isOpen || openedWithKeyboard ? 'text-on-surface-strong dark:text-on-surface-dark-strong' : 'text-on-surface dark:text-on-surface-dark'" x-bind:aria-expanded="isOpen || openedWithKeyboard">
                        {{ $label }}
                        @if($icon)
                            <span @class([$icon])></span>  
                        @endif    
                    </button>
                @endif
               
                <!-- Dropdown Menu -->
                <div x-cloak wire:key="dropdown-slot-{{ $uuid }}" x-show="isOpen || openedWithKeyboard" x-transition x-trap="openedWithKeyboard" x-on:click.outside="isOpen = false, openedWithKeyboard = false" x-on:keydown.down.prevent="$focus.wrap().next()" x-on:keydown.up.prevent="$focus.wrap().previous()" @class(["absolute flex w-fit min-w-48 flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt", $positionClass()]) role="menu">
                    {{ $slot }}
                </div>
            </div>

blade;

    }

    public function positionClass(): string
    {
        switch ($this->position) {
            case 'up':
                return 'bottom-11';
                case 'left':
                    return 'left-full ml-1 top-0';
                    case 'right':
                        return 'right-full mr-1 top-0';
                        default:
                            return 'top-11 left-0';
        }
    }
}