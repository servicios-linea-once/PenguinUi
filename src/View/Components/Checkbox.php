<?php

namespace PenguinUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Checkbox extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?bool $container = false,
        public ?string $hint = null,

        // Validations
        public ?string $errorField = null,
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,
    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function errorFieldName(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }
    /**
     * @inheritDoc
     */
    public function render(): View|Closure|string
    {
        return <<<'BLADE'
    <div @class(["relative w-fit","flex flex-col items-start" => $hint])>
        <label for="{{$uuid}}" 
        @class([
            'inline-flex items-center justify-between rounded-radius gap-3 border border-outline' => $container,
            'flex items-center gap-3' => ! $container,
            'text-sm font-medium text-on-surface has-checked:text-on-surface-strong has-disabled:cursor-not-allowed has-disabled:opacity-75',
            'dark:text-on-surface-dark dark:has-checked:text-on-surface-dark-strong'
        ])>
            <span class="relative flex items-center">
                <input id="{{$uuid}}" type="checkbox" 
                     {{$attributes->whereDoesntStartWith("id")}}
                    class="before:content[''] peer relative size-5 appearance-none overflow-hidden rounded-full border border-outline bg-surface-alt before:absolute before:inset-0 checked:border-primary checked:before:bg-primary focus:outline-2 focus:outline-offset-2 focus:outline-outline-strong checked:focus:outline-primary active:outline-offset-0 disabled:cursor-not-allowed dark:border-outline-dark dark:bg-surface-dark-alt dark:checked:border-primary-dark dark:checked:before:bg-primary-dark dark:focus:outline-outline-dark-strong dark:checked:focus:outline-primary-dark" checked/>
                <span class="icon-[line-md--check-all] pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-on-primary peer-checked:visible dark:text-on-primary-dark"></span>
            </span>
            <span>
                {{ $label }} 
                @if($attributes->has('required'))
                    <span class="text-red-500"> *</span>
                @endif
            </span>
        </label>
        @if($hint)
            <span id="{{$uuid}}-Description" class="ml-6 text-sm text-on-surface dark:text-on-surface-dark">{{ $hint }}</span>
        @endif
         {{-- ERROR --}}
        @if(!$omitError && $errors->has($errorFieldName()))
            @foreach($errors->get($errorFieldName()) as $message)
                @foreach(Arr::wrap($message) as $line)
                    <div id="{{$uuid}}-tooltipError" class="absolute -top-9 left-1/2 -translate-x-1/2 z-10 whitespace-nowrap rounded-sm bg-danger  px-2 py-1 text-center text-sm text-on-surface-dark-strong opacity-0 transition-all ease-out peer-hover:opacity-100 peer-focus:opacity-100 dark:bg-surface dark:text-on-surface-strong" role="tooltip">{{ $line }}</div>
                    @break($firstErrorOnly)
                @endforeach
                @break($firstErrorOnly)
            @endforeach
        @endif
    </div>
BLADE;

    }
}