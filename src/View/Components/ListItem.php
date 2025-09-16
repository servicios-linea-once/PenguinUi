<?php

    namespace PenguinUi\View\Components;

    use Closure;
    use Illuminate\Contracts\View\View;
    use Illuminate\View\Component;

    class ListItem extends Component
    {

        public string $uuid;

        public function __construct(
            public ?string $id = null,
            public ?string $label = null,
            public ?string $sublabel = null,
            public ?string $link = null,
            public ?string $avatar = null,
            public ?string $fallbackAvatar = null,
            public ?bool   $separator = false,
            public ?bool   $noHover = false,
            public ?bool   $external = false,
            public ?bool   $noWireNavigate = false,
            // Slots
            public mixed  $actions = null,
        )
        {
            $this->uuid = 'penguin-' . md5(serialize($this)) . $id;
        }
        /**
         * Get the view / contents that represent the component.
         */
        public function render(): View|Closure|string
        {
            return <<<'blade'
<div wire:key="{{ $uuid }}" @class([
            'border-b border-gray-200' => $separator,
            'hover:bg-outline/10 cursor-pointer' => !$noHover || $link,
            ])>
    <div {{$attributes->twMergeFor('content-list','flex flex-col gap-2 p-3 hover:shadow-lg rounded-radius', )}}>
        @if($link)
            <a href="{!! $link !!}"
            @if($external)
                target="_blank"
            @endif

            @if(!$external && !$noWireNavigate)
                wire:navigate
            @endif
        @else
            <div
        @endif
            class="flex items-center justify-between">
            <div class="flex items-center">
            @if($avatar)
                <img src="{{$avatar}}" {{$attributes->twMergeFor('avatar','size-10 object-cover rounded-radius')}} alt="{{$fallbackAvatar}}" aria-hidden="true"/>
            @endif
                <div {{$attributes->twMergeFor('main-list','flex-1 flex flex-col ml-3')}} >
                    <div {{$attributes->twMergeFor('label','font-medium leading-none')}}>{{ $label}}</div>
                    <p {{$attributes->twMergeFor('sublabel','text-sm text-gray-600 leading-none mt-1')}}>{{ $sublabel }}</p>
                </div>
            </div>
            @if($actions)
                <div {{$attributes->twMergeFor('actions','flex items-center')}}>
                    {{ $actions }}
                </div>
            @endif

        </div>
        @if($link)
            </a>
        @else
            </div>
        @endif

</div>
blade;
        }
    }