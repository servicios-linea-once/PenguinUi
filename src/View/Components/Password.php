<?php

namespace PenguinUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Password extends Component
{

    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $hint = null,

        // Slots
        public mixed $prefix = null,
        public mixed $suffix = null,

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error',
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

    public function isReadonly(): bool
    {
        return $this->attributes->has('readonly') && $this->attributes->get('readonly') === true;
    }

    public function isDisabled(): bool
    {
        return $this->attributes->has('disabled') && $this->attributes->get('disabled') === true;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
    <div class="my-0.5">
        <div class="w-full min-w-[200px] p-4 ">
          <div class="flex justify-between items-center mb-0.5">
            @if($label)
              <label {{ $attributes->twMergeFor('label', 'flex gap-0.5 item-center content-center mb-1 text-md text-slate-600 font-medium') }}  for="{{$uuid}}">
                {{$label}}
                @if($attributes->has('required'))
                    <span class="icon-[mdi--required] size-2.5 text-danger self-start ordinal"></span>
                @endif
              </label>
            @endif
             {{-- ERROR --}}
            @if(!$omitError && $errors->has($errorFieldName()))
                @foreach($errors->get($errorFieldName()) as $message)
                    @foreach(Arr::wrap($message) as $line)
                         <div class="relative w-fit">
                            <span class="peer icon-[iconamoon--information-circle-thin] size-4 text-danger"></span>
                            <div class="absolute -top-9 left-1/2 -translate-x-1/2 z-10 whitespace-nowrap rounded-sm bg-danger px-2 py-1 text-center text-xs text-on-danger opacity-0 transition-all ease-out peer-hover:opacity-100 peer-focus:opacity-100 dark:bg-surface dark:text-on-surface-strong line-clamp-3 max-w-sm" role="tooltip">{{ $line }}</div>
                         </div>
                        @break($firstErrorOnly)
                    @endforeach
                    @break($firstErrorOnly)
                @endforeach
            @endif

          </div>
          <div class="w-full overflow-hidden flex rounded-md border border-slate-200 focus-within:border-slate-400 hover:border-slate-300 transition duration-300 ease shadow-sm focus:shadow">
            @if($prefix)
                <div {{ $attributes->twMergeFor('prefix','border-r border-slate-200 flex items-center px-3 cursor-pointer') }}>
                  {{ $prefix }}
                </div>
            @endif
            <div class="relative flex-1"  x-data="{ show: false }" :type="show ? 'password' : 'text'">
                @if($icon)
                    <div {{$attributes->twMergeFor('icon','absolute top-2 left-0 flex items-center px-3')}}>
                        <span @class([$icon,'size-5'])></span>
                    </div>
                @endif

                <input
                    id="{{$uuid}}"
                    x-bind:type="show ? 'text' : 'password'"
                    @class([
                        '!pl-12.5' => $icon,
                        '!rounded-l-md' => !$suffix,
                        '!rounded-r-md' => !$prefix,
                        'pl-2.5' => !$icon,
                        '!border-danger' => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError,
                        'w-full bg-transparent border placeholder:text-slate-400 border-slate-400 focus:border-slate-300 text-slate-700 text-sm  py-2 transition duration-300 ease focus:outline-none shadow-sm focus:shadow',
                        'cursor-not-allowed bg-slate-100 text-slate-400 hover:border-slate-200 focus:border-slate-200 shadow-none focus:shadow-none' => $isDisabled(),
                        'cursor-default bg-slate-50 text-slate-400 hover:border-slate-200 focus:border-slate-200 shadow-none focus:shadow-none' => $isReadonly(),
                    ])
                    {{$attributes->merge(['type'=>'text','placeholder'=>''])}}
                   />
                   <div {{$attributes->twMergeFor('iconShow','absolute top-2 right-0 flex items-center px-3')}} >
                       <button type="button" @click="show=!show" class="z-30 cursor-pointer focus:outline-none">
                          <span x-show="!show" class="icon-[weui--eyes-on-outlined] size-5"></span>
                          <span x-show="show" class="icon-[weui--eyes-off-outlined] size-5"></span>
                       </button>
                    </div>
            </div>
            @if($suffix)
                <div {{$attributes->twMergeFor('suffix','border-l border-slate-200 flex items-center px-3 cursor-pointer')}}>
                  {{ $suffix }}
                </div>
            @endif
          </div>
          @if($hint)
              <small {{$attributes->twMergeFor('hint','pl-0.5 text-slate-500 text-sm')}}>{{ $hint }}</small>
          @endif
        </div>
    </div>
blade;
    }
}