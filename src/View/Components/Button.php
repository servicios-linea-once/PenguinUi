<?php

namespace PenguinUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use PenguinUi\Traits\ButtonColor;

class Button extends Component
{
    public string $uuid;
    public string $classButton;
    public string $badgeClasses;
    public string $tooltipPosition = 'lg:tooltip-top';

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $iconRight = null,
        public ?string $spinner = null,
        public ?string $link = null,
        public ?bool $external = false,
        public ?bool $noWireNavigate = false,
        public ?bool $responsive = false,
        public ?string $badge = null,
        public ?string $tooltip = null,
        public ?string $tooltipLeft = null,
        public ?string $tooltipRight = null,
        public ?string $tooltipBottom = null,
        public ?string $variant = 'default',
        public ?string $color = 'primary',
    ) {
        $this->uuid = "penguin" . md5(serialize($this)) . $id;
        $this->classButton = $this->setClassButton();
        $this->badgeClasses = 'badge';

//        $this->tooltip = $this->tooltip ?? $this->tooltipLeft ?? $this->tooltipRight ?? $this->tooltipBottom;
//        $this->tooltipPosition = $this->tooltipLeft ? 'lg:tooltip-left' : ($this->tooltipRight ? 'lg:tooltip-right' : ($this->tooltipBottom ? 'lg:tooltip-bottom' : 'lg:tooltip-top'));
    }

    public function spinnerTarget(): ?string
    {
        if ($this->spinner == 1) {
            return $this->attributes->whereStartsWith('wire:click')->first();
        }

        return $this->spinner;
    }

    public function render(): View|Closure|string
    {

        return <<<'BLADE'
                @if($link)
                    <a href="{!! $link !!}"
                @else
                    <button
                @endif

                    wire:key="{{ $uuid }}"
                    {{ $attributes->whereDoesntStartWith('class')->merge(['type' => 'button']) }}
                    {{ $attributes->class(['inline-flex justify-center items-center gap-2 cursor-pointer',$classButton]) }}

                    @if($link && $external)
                        target="_blank"
                    @endif

                    @if($link && !$external && !$noWireNavigate)
                        wire:navigate
                    @endif


                    @if($spinner)
                        wire:target="{{ $spinnerTarget() }}"
                        wire:loading.attr="disabled"
                    @endif
                >

                    <!-- SPINNER LEFT -->
                    @if($spinner && !$iconRight)
                        <span wire:loading wire:target="{{ $spinnerTarget() }}" class="icon-[eos-icons--bubble-loading] w-5 h-5"></span>
                    @endif

                    <!-- ICON -->
                    @if($icon)
                        <span class="block" @if($spinner) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif>
                          <span class="{{$icon}}"></span>
                        </span>
                    @endif

                    <!-- LABEL / SLOT -->
                    @if($label)
                        <span @class(["hidden lg:block" => $responsive ])>
                            {{ $label }}
                        </span>
                        @if(strlen($badge ?? '') > 0)
                            <span class="{{ $badgeClasses }}">{{ $badge }}</span>
                        @endif
                    @else
                        {{ $slot }}
                    @endif

                    <!-- ICON RIGHT -->
                    @if($iconRight)
                        <span class="block" @if($spinner) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif>
                            <span class="{{$iconRight}}"></span>
                        </span>
                    @endif

                    <!-- SPINNER RIGHT -->
                    @if($spinner && $iconRight)
                        <span wire:loading wire:target="{{ $spinnerTarget() }}" class="icon-[eos-icons--bubble-loading] w-5 h-5"></span>
                    @endif

                @if(!$link)
                    </button>
                @else
                    </a>
                @endif
            BLADE;
    }
    protected function setClassButton()
    {
        $class='';
        $array = [
            'primary' ,
            'secondary' ,
            'alternate' ,
            'inverse' ,
            'info' ,
            'danger' ,
            'warning',
            'success',
        ];
        if (! Arr::has($array,$this->color)){
            $color = 'primary';
        }
        switch ($this->variant){
            case 'outline':
                $class = "button-outline-". $this->color;
                break;
            case 'ghost':
                $class = "button-ghost-". $this->color;
                break;
            default:
                $class = "button-". $this->color;
                break;
        }
        return $class;
    }


}