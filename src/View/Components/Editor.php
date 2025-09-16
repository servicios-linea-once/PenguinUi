<?php

namespace PenguinUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Editor extends Component
{

    public string $uuid;
    /*
     * <link type="text/css" rel="stylesheet" href="es2021/jodit.min.css" />
     * <script type="text/javascript" src="es2021/jodit.min.js"></script>
    */
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $disableplugins = 'ai-assistant,file,image,image-processor,image-properties,video,media',
        public ?array $config = [],
        public ?bool $readonly = false,

        // Validations

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

    public function setup(): string
    {
        $setup = array_merge([
            'width' => '100%',
            'height' => 200,
            'readonly' => $this->readonly,
            'toolbarButtonSize' =>'small',
            'language' =>'es',
            "disablePlugins"=> $this->disableplugins
        ], $this->config);

        return str(json_encode($setup))->trim('{}')->replace("\"", "'")->toString();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
                @php
                    // Wee need this extra step to support models arrays. Ex: wire:model="emails.0"  , wire:model="emails.1"
                    $uuid = $uuid . $modelName()
                @endphp
    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark"
    x-data="{
    value: @entangle($attributes->wire('model')),
    }"
    x-init="
    Jodit.make($refs.jodit, {
        {{$setup}}
      });"
    wire:ignore>
        <label for="{{ $id ?? $uuid }}" class="w-fit pl-0.5 text-sm">{{ $label }}</label>
        <textarea id="{{ $id ?? $uuid }}" x-ref="jodit" class="w-full rounded-radius border border-outline bg-surface-alt px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"  {{ $attributes->whereDoesntStartWith('wire:model') }}></textarea>
    </div>
blade;
    }
}