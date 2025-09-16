<?php

namespace PenguinUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class File extends Component
{

    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $preview = false,
        public ?bool $multiple = false,

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
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
    <div
        x-data="{
            files: [],
            filesNames: [],
            multiple: {{$multiple ? 'true' : 'false'}},
            humanFileSize(size) {
                const i = Math.floor(Math.log(size) / Math.log(1024));
                return (
                    (size / Math.pow(1024, i)).toFixed(2) * 1 +
                    ' ' +
                    ['B', 'kB', 'MB', 'GB', 'TB'][i]
                );
            },
            async uploadFiles(files) {
                const $this = this
                this.isUploading = true
                try {
                    @this.uploadMultiple('{{ $attributes->whereStartsWith('wire:model')->first() }}', files,
                        function (success) {  //upload was a success and was finished
                            $this.isUploading = false
                            $this.progress = 0
                            $this.setNombreFiles(success)
                            const file = createFileList([...$this.files], [...files]);
                            $this.files = file;
                            $this.filesNames.forEach(function(nombre, index) {
                                $this.files[index].namealt = nombre;
                            })
                        },
                        function(error) {  //an error occured
                            console.log('error', error)
                        },
                        function (event) {  //upload progress was made
                            $this.progress = event.detail.progress
                        }
                    )
                }
                catch (error) {
                    console.error('Error fetching data:', error);
                }
            },
            addFiles(e) {
                 if (e.target.files.length) {
                    if(! this.multiple){ this.files = []}
                    this.uploadFiles(e.target.files)
                }
            },
            setNombreFiles(e){
                this.filesNames = e.reverse();
            },
            remove(index) {
                let files = [...this.files];
                files.splice(index, 1);
                this.files = createFileList(files);
                @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', index.namealt)
            },
            loadFile(file) {
                const preview = document.querySelectorAll('.preview');
                const blobUrl = URL.createObjectURL(file);

                preview.forEach(elem => {
                    elem.onload = () => {
                        URL.revokeObjectURL(elem.src);
                    };
                });
                return blobUrl;
            },
        }"
          {{ $attributes->whereStartsWith('class') }}
    >
        <div

            @dragover="$refs.file.classList.add('border-primary');"
            @dragleave="$refs.file.classList.remove('border-primary'); "
            @drop="$refs.file.classList.remove('border-primary');"
         class="relative flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
          {{-- STANDARD LABEL --}}
             @if($label)
                <label for="{{ $uuid }}" class="w-fit pl-0.5 text-sm">{{ $label }}
                @if($attributes->get('required'))
                    <span class="text-error">*</span>
                @endif
                </label>
            @endif
            <input id="{{ $uuid }}"
                            type="file"
                            x-ref="file"
                            @if($multiple)
                                multiple
                            @endif
                            @change="addFiles"
                            @class([
                                'w-full overflow-clip rounded-radius border border-outline bg-surface-alt/50 text-sm file:mr-4 file:border-none file:bg-surface-alt file:px-4 file:py-2 file:font-medium file:text-on-surface-strong focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:file:bg-surface-dark-alt dark:file:text-on-surface-dark-strong dark:focus-visible:outline-primary-dark',
                                "!border-danger" => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError,
                            ])
                            />
                            @if($hint)
                                <small class="pl-0.5">{{$hint}}</small>
                            @endif
        </div>
        @if($preview)
            <template x-if="files.length > 0">
                <div class="grid grid-cols-2 gap-4 mt-4 md:grid-cols-6">
                    <template x-for="(_, index) in Array.from({ length: files.length })">
                        <div class="relative flex flex-col items-center overflow-hidden text-center bg-gray-100 border rounded select-none"
                            style="padding-top: 100%;"
                            :data-index="index">
                            <button class="absolute top-0 right-0 z-50 p-1 bg-white rounded-bl focus:outline-none" type="button" @click="remove(files[index])">
                                <svg class="w-4 h-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            <template x-if="files[index].type.includes('audio/')">
                                <svg class="absolute w-12 h-12 text-gray-400 transform top-1/2 -translate-y-2/3"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                            </template>
                            <template x-if="files[index].type.includes('application/') || files[index].type === ''">
                                <svg class="absolute w-12 h-12 text-gray-400 transform top-1/2 -translate-y-2/3"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </template>
                            <template x-if="files[index].type.includes('image/')">
                                <img class="absolute inset-0 z-0 object-cover w-full h-full border-4 border-white preview"
                                    x-bind:src="loadFile(files[index])" />
                            </template>
                            <template x-if="files[index].type.includes('video/')">
                                <video
                                    class="absolute inset-0 object-cover w-full h-full border-4 border-white pointer-events-none preview">
                                    <fileDragging x-bind:src="loadFile(files[index])" type="video/mp4">
                                </video>
                            </template>

                            <div class="absolute bottom-0 left-0 right-0 flex flex-col p-2 text-xs bg-white bg-opacity-50">
                                <span class="w-full font-bold text-gray-900 truncate"
                                    x-text="files[index].name">Loading</span>
                                <span class="text-xs text-gray-900" x-text="humanFileSize(files[index].size)">...</span>
                            </div>

                        </div>
                    </template>
                </div>
            </template>
        @endif
    </div>
    blade;
    }
}