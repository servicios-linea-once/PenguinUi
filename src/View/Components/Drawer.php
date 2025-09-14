<?php

namespace PenguinUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Livewire\WireDirective;

class Drawer extends Component
{
    public string $uuid;
    public string $classRight;

    public function __construct(
        public ?string $id = null,
        public ?bool $right = false,
        public ?string $width = 'w-80',
        public ?string $title = null,
        public ?bool $withCloseButton = true,
        public ?bool $closeOnEscape = true,
        public ?bool $withoutTrapFocus = true,

        //Slots
        public ?string $actions = null,
        public ?string $slot = null
    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
        if ($this->right) {$this->classRight = "right-0 border-l";} else {$this->classRight = "left-0 border-r";};
    }
    public function id(): string
    {
        return $this->id ?? $this->attributes?->wire('model')->value();
    }

    public function modelName(): WireDirective
    {
        return $this->attributes->wire('model');
    }

    /**
     * @inheritDoc
     */
    public function render(): View|Closure|string
    {
        return <<<'BLADE'
            <div x-data="{ sidebarIsOpen: @if($modelName()->value) @entangle($modelName()) @else false @endif
                            ,
                            close() {
                                this.sidebarIsOpen = false
                            } }" 
                          
                          @if($closeOnEscape)
                                @keydown.window.escape="close()"
                                x-on:keydown.esc.window="close()"
                            @endif
                          x-on:click.outside="close()" >
                <!-- toggle button -->
                <x-button label="Open" x-on:click="sidebarIsOpen = ! sidebarIsOpen"></x-button>
            
                <div x-cloak x-show="sidebarIsOpen"
                    @if(!$withoutTrapFocus)
                        x-trap="sidebarIsOpen" x-bind:inert="!sidebarIsOpen"
                    @endif
                    @class([$classRight,$width,'fixed top-0 z-50 flex h-svh w-80 shrink-0 flex-col border-outline bg-surface-alt py-3 px-5 transition-transform duration-300 dark:border-outline-dark dark:bg-surface-dark-alt'])
                    x-transition:enter="transition duration-200 ease-out" 
                    x-transition:enter-end="translate-x-0" 
                    x-transition:leave="transition ease-in duration-200 "
                    @if($right)
                          x-transition:enter-start=" translate-x-80" 
                          x-transition:leave-end="translate-x-80" 
                    @else
                         x-transition:enter-start=" -translate-x-80" 
                        x-transition:leave-end=" -translate-x-80" 
                    @endif
                  
                    x-transition:leave-start="translate-x-0">
                    <!-- sidebar header -->
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-on-surface-strong dark:text-on-surface-dark-strong">{{ $title }}</h3>
                          @if($withCloseButton)
                                <button class="text-on-surface dark:text-on-surface-dark cursor-pointer" x-on:click="sidebarIsOpen = false">
                                    <span class="icon-[line-md--close-circle] size-6"></span>
                                    <span class="sr-only">Cerrar barra lateral</span>
                                </button>
                           @endif
                    </div>
            
                    <!-- sidebar Contain -->
                    <div {{ $attributes->except('wire:model')->class(['flex flex-col gap-2 overflow-y-auto py-2 h-full']) }}>{{$slot}}</div>
            
                    <!-- sidebar footer -->
                    @if($actions)
                        <div class="mt-auto flex justify-end gap-2">
                            {{ $actions }}
                        </div>
                    @endif
                </div>
            </div>
BLADE;

    }
}