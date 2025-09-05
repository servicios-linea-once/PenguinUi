<?php

namespace PenguinUi\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

//accordion-item
class Collapse extends Component
{    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?bool $collapsePlusMinus = false,
        public ?bool $separator = false,
        public ?bool $noIcon = false,

        // Slots
        public mixed $heading = null,
        public mixed $content = null,
    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
    }
    public function render(): View | \Closure | string
    {
        return <<<'BLADE'
        <div wire:key="collapse-{{ $uuid }}">
            <button id="{{ $uuid }}" type="button" {{ $heading->attributes->class(['flex w-full items-center justify-between gap-4 bg-surface-alt p-4 text-left underline-offset-2 hover:bg-surface-alt/75 focus-visible:bg-surface-alt/75 focus-visible:underline focus-visible:outline-hidden dark:bg-surface-dark-alt dark:hover:bg-surface-dark-alt/75 dark:focus-visible:bg-surface-dark-alt/75'])->merge() }} class="" 
            aria-controls="accordionItemOne" 
            x-on:click="selectedAccordionItem = '{{$name}}'" 
            x-bind:class="selectedAccordionItem === '{{$name}}' ? 'text-on-surface-strong dark:text-on-surface-dark-strong font-bold'  : 'text-on-surface dark:text-on-surface-dark font-medium'" 
            x-bind:aria-expanded="selectedAccordionItem === '{{$name}}' ? 'true' : 'false'">
               {{ $heading }}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke="currentColor" class="size-5 shrink-0 transition" aria-hidden="true" x-bind:class="selectedAccordionItem === 'one'  ?  'rotate-180'  :  ''">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-cloak x-show="selectedAccordionItem === '{{$name}}'" id="accordionItemOne" role="region" aria-labelledby="controlsAccordionItemOne" x-collapse>
                <div class="p-4 text-sm sm:text-base text-pretty"  {{ $content->attributes->merge(["class" => "collapse-content text-sm"]) }} wire:key="content-{{ $uuid }}">
                     {{ $content }}
                </div>
            </div>
        </div>
        BLADE;

    }
}