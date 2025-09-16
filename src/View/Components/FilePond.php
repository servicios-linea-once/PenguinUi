<?php

namespace PenguinUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilePond extends Component
{
    public string $uuid;
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $id = null,
        public ?array $config = [],
        public ?string $acceptedFileTypes = null,
        public ?bool $multiple = false,
        public ?bool $preview = true,
        public ?bool $allowFileSizeValidation = false,
        public ?bool $allowFileTypeValidation = false,
        public ?int $imagePreviewMaxHeight = 256,
        public ?string $maxFileSize = '256mb',
        public ?string $labelIdle = 'Arrastre y Suelta una foto o <span class="filepond--label-action">Navegar</span>',
    )
    {
        $this->uuid = "penguin" . md5(serialize($this)) . $id;
    }
    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }
    public function setup(): string
    {
        return json_encode(array_merge([
            'allowMultiple' => $this->multiple,
            'allowImagePreview' => $this->preview,
            'imagePreviewMaxHeight' => $this->imagePreviewMaxHeight ,
            'allowFileTypeValidation' => $this->allowFileTypeValidation,
            'allowFileSizeValidation' => $this->allowFileSizeValidation,
            'maxFileSize' => $this->maxFileSize,
            'labelIdle'=> $this->labelIdle,
        ], $this->config), JSON_THROW_ON_ERROR);
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
        <div
            wire:ignore
            x-data
            x-init="
                () => {
                    const post = FilePond.create($refs.{{ $uuid }});
                    post.setOptions({
                        server: {
                            process:(fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                                @this.upload('{{ $modelName }}', file, load, error, progress)
                            },
                            revert: (filename, load) => {
                                @this.removeUpload('{{ $modelName }}', filename, load)
                            },
                        },
                        acceptedFileTypes: {!! $acceptedFileTypes ?? 'null' !!},
                    });
                    post.setOptions({{ $setup() }});
                }
            "
        >
            <input type="file" id="{{ $uuid }}" x-ref="{{ $uuid }}" />

        </div>

blade;
    }
}