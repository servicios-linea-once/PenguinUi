<?php

namespace PenguinUi\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Errors extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $icon = 'o-x-circle',
        public ?array $only = [],
    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
    }

    public function render(): View|\Closure|string
    {
        return <<<'blade'
    <div class="relative w-full overflow-hidden rounded-radius border border-danger bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark" role="alert">
    @if ($errors->any())
        <div class="flex w-full items-center gap-2 bg-danger/10 p-4">
            <div class="bg-danger/15 text-danger rounded-full p-1" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-2">
                @if($title)
                    <h3 class="text-sm font-semibold text-danger">{{ $title }}</h3>
                @endif
                @if($description)
                    <p class="text-xs font-medium sm:text-sm">{{ $description }}</p>
                @endif
                <ul class="mt-2 list-inside list-disc pl-2 text-xs font-medium text-danger sm:text-sm">
                     @foreach ($errors->all() as $error)
                       <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
blade;

    }
}