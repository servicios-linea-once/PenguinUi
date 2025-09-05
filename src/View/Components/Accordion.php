<?php

namespace PenguinUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Accordion extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?int $gap = null
    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
    }

    /**
     * @inheritDoc
     */
    public function render(): View|Closure|string
    {
        return <<<'BLADE'
        <div x-data="{ selectedAccordionItem: @entangle($attributes->wire('model')) }" 
        {{ $attributes->whereDoesntStartWith('wire:model')->merge(['class']) }}
                    wire:key="accordion-{{ $uuid }}"
        class="w-full divide-y divide-outline overflow-hidden rounded-sm border border-outline bg-surface-alt/40 text-on-surface dark:divide-outline-dark dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark">
         {{ $slot }}
        </div>    
        BLADE;
    }
}