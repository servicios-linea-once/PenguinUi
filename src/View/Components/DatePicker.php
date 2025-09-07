<?php

namespace PenguinUi\View\Components;

use Illuminate\Support\Arr;
use Illuminate\View\Component;

class DatePicker extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $iconRight = null,
        public ?string $hint = null,
        public ?string $hintClass = 'fieldset-label',
        public ?bool $inline = false,
        public ?array $config = [],

        // Slots
        public mixed $prepend = null,
        public mixed $append = null,

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
        return $this->attributes->has('readonly') && $this->attributes->get('readonly') == true;
    }

    public function isDisabled(): bool
    {
        return $this->attributes->has('disabled') && $this->attributes->get('disabled') == true;
    }
    public function setup(): string
    {
        // Manejar `wire:model.live` para fechas de` rango '
        if (isset($this->config["mode"]) && $this->config["mode"] == "range" && $this->attributes->wire('model')->hasModifier('live')) {
            $this->attributes->setAttributes([
                'wire:model' => $this->modelName(),
                'live' => true
            ]);
        }

        $config = json_encode(array_merge([
            'dateFormat' => 'Y-m-d H:i',
            'altInput' => true,
            'altInputClass' => 'w-full rounded-radius border border-outline bg-surface-alt pl-4 pr-10 py-3 leading-none shadow-sm focus:outline-none focus:shadow-outline text-gray-600 font-medium',
            'clickOpens' => ! $this->attributes->has('readonly') || $this->attributes->get('readonly') == false,
            'defaultDate' => '#model#',
            'plugins' => ['#plugins#'],
            'disable' => ['#disable#'],
        ], Arr::except($this->config, ["plugins"])));

        // Plugins
        $plugins = "";

        foreach (Arr::get($this->config, 'plugins', []) as $plugin) {
            $plugins .= "new " . key($plugin) . "( " . json_encode(current($plugin)) . " ),";
        }

        $config = str_replace('"#plugins#"', $plugins, $config);

        // Disables
        $disables = '';

        foreach (Arr::get($this->config, 'disable', []) as $disable) {
            $disables .= $disable . ',';
        }

        $config = str_replace('"#disable#"', $disables, $config);

        // Sets default date as current bound model
        $config = str_replace('"#model#"', '$wire.get("' . $this->modelName() . '")', $config);

        return $config;
    }

    public function render() {
        return <<<'BLADE'
            <div wire:key="datepicker-{{ rand() }}" x-data="{ selectedDate:'' }"
            x-init="flatpickr($refs.dateInput, {{ $setup() }} );"
             x-cloak
             @if(isset($config["mode"]) && $config["mode"] == "range" && $attributes->get('live'))
                    @change="const value = $event.target.value; if(value.split(instance.l10n.rangeSeparator).length == 2) { $wire.set('{{ $modelName() }}', value) };"
                @endif
                x-on:livewire:navigating.window="instance.destroy();"
             >
                @php
                    // We need this extra step to support models arrays. Ex: wire:model="emails.0"  , wire:model="emails.1"
                    $uuid = $uuid . $modelName()
                @endphp
                <div class="container mx-auto px-4 py-2 md:py-10" >
                    <label for="{{$uuid}}" class="font-bold mb-1 text-gray-700 block">Fecha de selección</label>
                    <div class="relative">
                        <input
                            type="text"
                            x-ref="dateInput" 
                            x-model="selectedDate"
                            readonly
                            id="{{$uuid}}"
                            class=""
                            placeholder="Select date">
                        <div class="absolute top-0 right-0 px-3 py-2">
                            <svg class="h-6 w-6 text-gray-400"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
BLADE;

    }
}