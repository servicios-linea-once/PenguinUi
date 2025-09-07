<?php

namespace PenguinUi\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Select extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $placeholder = null,
        public Collection|array $options = new Collection(),
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
     * ['value'=> 'label']
     */
    public function render()
    {
        return <<<'BLADE'
        <div
            x-data="{
            data: @js($options),
            emptyOptionsMessage: 'No countries match your search.',
            name: '{{$label}}',
            placeholder: '{{$placeholder}}',
            value: @entangle($attributes->wire('model')),
            focusedOptionIndex: null,
            open: false,
            options: {},
            search: '',
            closeListbox: function () {
                this.open = false
                this.focusedOptionIndex = null
                this.search = ''
             },
             focusNextOption: function () {
                    if (this.focusedOptionIndex === null) return this.focusedOptionIndex = Object.keys(this.options).length - 1
                    if (this.focusedOptionIndex + 1 >= Object.keys(this.options).length) return
                    this.focusedOptionIndex++
                    this.$refs.listbox.children[this.focusedOptionIndex].scrollIntoView({block: 'center'})
              },
              focusPreviousOption: function () {
                    if (this.focusedOptionIndex === null) return this.focusedOptionIndex = 0
                    if (this.focusedOptionIndex <= 0) return
                    this.focusedOptionIndex--
                    this.$refs.listbox.children[this.focusedOptionIndex].scrollIntoView({block: 'center',})
              },
              init: function () {
                this.options = this.data
                if (!(this.value in this.options)) this.value = null
                this.$watch('search', ((value) => {
                        if (!this.open || !value) return this.options = this.data
                        this.options = Object.keys(this.data).filter((key) => this.data[key].toLowerCase().includes(value.toLowerCase()))
                        .reduce((options, key) => {
                                options[key] = this.data[key]
                                return options
                         }, {})
                 }))
              },

                selectOption: function () {
                    if (!this.open) return this.toggleListboxVisibility()
                    this.value = Object.keys(this.options)[this.focusedOptionIndex]
                    @this.set('$attributes->wire('model'),this.value)
                    this.closeListbox()
                },
        
                toggleListboxVisibility: function () {
                    if (this.open) return this.closeListbox()
                    this.focusedOptionIndex = Object.keys(this.options).indexOf(this.value)
                    if (this.focusedOptionIndex < 0) this.focusedOptionIndex = 0
                    this.open = true
                    this.$nextTick(() => {
                        this.$refs.search.focus()
                        this.$refs.listbox.children[this.focusedOptionIndex].scrollIntoView({block: 'center'})
                    })
                },
            }"
            x-init="init()"
            @click.away="closeListbox()"
            @keydown.escape="closeListbox()"
            class="relative"
        >
        
            @php
                // Necesitamos este paso adicional para admitir matrices de modelos. Ex: wire:model="emails.0"  , wire:model="emails.1"
                $uuid = $uuid . $modelName()
            @endphp
            <label for="{{ $uuid }}" class="w-fit pl-0.5 text-sm uppercase" x-text="name"></label>
            <span class="inline-block w-full rounded-md shadow-sm">
                  <button
                      x-ref="button"
                      @click="toggleListboxVisibility()"
                      :aria-expanded="open"
                      aria-haspopup="listbox"
                      class="relative z-0 w-full py-2 pl-3 pr-10 text-left transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md cursor-default focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5"
                  >
                        <span x-show="! open" x-text="value in options ? options[value] : placeholder" :class="{ 'text-gray-500': ! (value in options) }" class="block truncate"></span>

                        <input
                             id="{{ $uuid }}"
                            x-ref="search" x-show="open" x-model="search" @keydown.enter.stop.prevent="selectOption()" @keydown.arrow-up.prevent="focusPreviousOption()"
                            @keydown.arrow-down.prevent="focusNextOption()" type="search" class="w-full h-full form-control focus:outline-none"
                        />

                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <span class="icon-[quill--expand] w-5 h-5 text-gray-400"></span>
                        </span>
                  </button>
            </span>

            <div
                x-show="open"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-cloak
                class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg"
            >
                <ul
                    x-ref="listbox"
                    @keydown.enter.stop.prevent="selectOption()"
                    @keydown.arrow-up.prevent="focusPreviousOption()"
                    @keydown.arrow-down.prevent="focusNextOption()"
                    role="listbox"
                    :aria-activedescendant="focusedOptionIndex ? name + 'Option' + focusedOptionIndex : null"
                    tabindex="-1"
                    class="py-1 overflow-auto text-base leading-6 rounded-md shadow-xs max-h-60 focus:outline-none sm:text-sm sm:leading-5"
                >
                    <template x-for="(key, index) in Object.keys(options)" :key="index">
                        <li
                            :id="name + 'Option' + focusedOptionIndex"
                            @click="selectOption()"
                            @mouseenter="focusedOptionIndex = index"
                            @mouseleave="focusedOptionIndex = null"
                            role="option"
                            :aria-selected="focusedOptionIndex === index"
                            :class="{ 'text-white bg-indigo-600': index === focusedOptionIndex, 'text-gray-900': index !== focusedOptionIndex }"
                            class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9"
                        >
                                <span x-text="Object.values(options)[index]" :class="{ 'font-semibold': index === focusedOptionIndex, 'font-normal': index !== focusedOptionIndex }" class="block font-normal truncate"></span>

                            <span x-show="key === value" :class="{ 'text-white': index === focusedOptionIndex, 'text-indigo-600': index !== focusedOptionIndex }" class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">
                                    <span class="icon-[ic--outline-check] size-5"></span>
                            </span>
                        </li>
                    </template>

                    <div x-show="! Object.keys(options).length"  x-text="emptyOptionsMessage" class="px-3 py-2 text-gray-900 cursor-default select-none"></div>
                </ul>
            </div>
        </div>
BLADE;

    }
}