<?php

namespace PenguinUi\View\Components;

use Illuminate\View\Component;

class Avatar extends Component
{
    public string $uuid;
    public string $classzise;

    /**
     * @param  ?string  $image  The URL of the avatar image.
     * @param  ?string  $alt  The HTML `alt` attribute
     * @param  ?string  $size  The size of the avatar.
     * @param  ?string  $color  The color of border of the avatar.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $image = '',
        public ?string $alt = '',
        public ?string $size = 'md',
        public ?string $color = 'primary',
        public ?bool $badge = false,

    ) {
        $this->uuid = "penguin" . md5(serialize($this)) . $id;
        $this->classzise = $this->setSizeClass();
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        return <<<'BLADE'
            <div class="relative w-fit">
                <img class="border-2 border-primary rounded-full object-cover object-center" src="{{$image}}" alt="{{ $alt }}"
                {{ $attributes->class([$classzise]) }}>
                @if($badge')
                    <span class="absolute size-4 bottom-0.5 end-0 rounded-full border-2 border-surface dark:border-surface-dark bg-outline dark:bg-outline-dark"></span> 
                @endif
            </div>
BLADE;

    }
    public function setSizeClass(){
        $class = '';
        switch ($this->size){
            case 'xs':  $class = 'size-6'; break;
            case 'sm':  $class = 'size-8'; break;
            case 'lg':  $class = 'size-14'; break;
            case 'xl':  $class = 'size-20'; break;
            case '2xl':  $class = 'size-'; break;
            default:  $class = 'size-10'; break;
        };
        return $class;
    }
}