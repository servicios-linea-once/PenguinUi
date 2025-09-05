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
                    x-data="{ alertIsVisible: true }" x-show="alertIsVisible"
                >
                    <div class="flex w-full items-center gap-2 bg-info/10 p-4">
                        @if($icon)
                            <div class="bg-blue-500/15 text-blue-500 rounded-full p-1 flex items-center" aria-hidden="true">
                                <span class="{{$icon}} size-6 sefl-center"></span>
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
                            <span class="icon-[line-md--close] size-4 shrink-0 ml-auto cursor-pointer" aria-label="Alerta de despedida" @click="alertIsVisible = false"></span>
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