<?php

namespace PenguinUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $uuid;

    /**
     * @param ?string  $title  The title of the alert, displayed in bold.
     * @param ?string  $icon  The icon displayed at the beginning of the alert.
     * @param ?string  $description  A short description under the title.
     * @param ?bool  $shadow  Whether to apply a shadow effect to the alert.
     * @param ?bool  $dismissible  Whether the alert can be dismissed by the user.
     * @slot  mixed  $actions  Slots for actionable elements like buttons or links.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $icon = null,
        public ?string $description = null,
        public ?string $color = 'info',
        public ?bool $shadow = false,
        public ?bool $dismissible = false,

        // Slots
        public mixed $actions = null
    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
    }
    /**
     * @inheritDoc
     */
    public function render(): View|Closure|string
    {
        return <<<'BLADE'
                <div
                    wire:key="{{ $uuid }}"
                    {{ $attributes->whereDoesntStartWith('class') }}
                    {{ $attributes->class(['shadow-lg' => $shadow])}}
                    x-data="{ show: true }" x-show="show"
                >
                    <div class="flex w-full items-center gap-2 bg-info/10 p-4">
                        @if($icon)
                            <div class="bg-blue-500/15 text-blue-500 rounded-full p-1" aria-hidden="true">
                                <span class="{{$icon}}"></span>
                            </div>
                        @endif
                        @if($actions)
                            <div class="flex flex-col gap-2 ml-2">
                        @endif
                            @if($title)
                                <div class="ml-2">
                                    <h3 class="text-sm font-semibold text-info">{{ $title }}</h3>
                                    @if($description)
                                        <p class="text-xs font-medium sm:text-sm">{{ $description }}</p>
                                    @endif
                                </div>                         
                            @else
                                <span>{{ $slot ?? $description }}</span>
                            @endif
                        @if($actions)
                                 <div class="flex items-center gap-4">
                                    {{ $actions }}
                                </div>
                            </div>
                        @endif
                       
                        @if($dismissible)
                            <button class="ml-auto" aria-label="dismiss alert">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2.5" class="size-4 shrink-0">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
BLADE;

    }

    public function setColorClass(){
        $array = [
            'info',
            'success',
            'warning',
            'danger'
        ];
        if (! Arr::has($array,$this->color)){
            $color = 'info';
        }else{
            $color = Arr::get($array,$this->color);
        }
        return "alert-". $color;
    }
}