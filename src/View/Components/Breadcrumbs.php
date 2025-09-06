<?php

namespace PenguinUi\View\Components;

use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    public string $uuid;

    /**
     * @param  array  $items  The steps that should be displayed. Each element supports the keys 'label', 'link', 'icon' and 'tooltip'.
     * @param  string  $separator  Any supported icon name, 'o-slash' by default.
     * @param ?string  $linkItemClass  The classes that should be applied to each item with a link.
     * @param ?string  $textItemClass  The classes that should be applied to each item without a link.
     * @param ?string  $iconClass  The classes that should be applied to each items icon.
     * @param ?string  $separatorClass  The classes that should be applied to each separator.
     * @param ?bool  $noWireNavigate  If true, the component will not use wire:navigate on links.
     */
    public function __construct(
        public ?string $id = null,
        public array $items = [],
        public string $separator = 'icon-[material-symbols--chevron-right]',
        public ?string $linkItemClass = "hover:underline text-sm",
        public ?string $textItemClass = "text-sm",
        public ?string $iconClass = "h-4 w-4",
        public ?string $separatorClass = "h-3 w-3 mx-1 text-base-content/40",
        public ?bool $noWireNavigate = false,
    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
    }
    /**
     * @inheritDoc
     */
    public function render()
    {
        return <<<'BLADE'
        <nav class="text-sm font-medium text-on-surface dark:text-on-surface-dark" aria-label="breadcrumb">
            <ol class="flex flex-wrap items-center gap-1">
            @foreach($items as $element)
                <li class="flex items-center gap-1" @class(['text-on-surface-strong font-bold dark:text-on-surface-dark-strong' => $loop->last])>
                        @if ($element['link'] ?? null)
                                <a href="{{ $element['link'] }}" class="hover:text-on-surface-strong dark:hover:text-on-surface-dark-strong" @if(!$noWireNavigate) wire:navigate @endif @class([$linkItemClass])>
                            @else
                                <span @class([$textItemClass])>
                            @endif

                                {{-- Icon --}}
                                @if($element['icon'] ?? null)
                                    <span class="{{ $element['icon'] }}" @class(["mb-0.5", $iconClass])></span>
                                @endif

                                {{-- Text --}}
                                <span>
                                    {{ $element['label'] ?? null }}
                                </span>

                            @if ($element['link'] ?? null)
                                </a>
                            @else
                                </span>
                            @endif
                             {{-- Separator --}}
                            <span @class([
                                    "hidden",
                                    "!block" => ($loop->first || $loop->remaining == 1) && $loop->count > 1,
                                    "sm:!block" => !$loop->last && $loop->count > 1
                                 ])
                            >
                                <span @class([$separator,$separatorClass]) ></span>
                            </span>
                   
                </li>
             @endforeach
            </ol>
        </nav>
BLADE;
    }
}