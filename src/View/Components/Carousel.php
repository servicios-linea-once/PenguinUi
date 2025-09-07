<?php

namespace PenguinUi\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\View\View;

class Carousel extends Component
{
    public string $uuid;

    public function __construct(
        public array $slides,
        public ?string $id = null,
        public ?bool $withoutIndicators = true,
        public ?bool $withoutArrows = true,
        public ?bool $withoutPlay = false,
        public ?bool $autoplay = false,
        public ?int $interval = 2000,

    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
    }

    /**
     * @inheritDoc
     */
    public function render(): View|Closure|string
    {
        return <<<'BLADE'
        <div x-data="{
            // Establece el tiempo entre cada diapositiva en milisegundos
            autoplayIntervalTime: {{ json_encode($interval) }},
            slides: @js($slides),
            currentSlideIndex: 1,
            isPaused: false,
            autoplayInterval: null,
            previous() {
                if (this.currentSlideIndex > 1) {
                    this.currentSlideIndex = this.currentSlideIndex - 1
                } else {
                    // Si es la primera diapositiva, ve a la última diapositiva
                    this.currentSlideIndex = this.slides.length
                }
            },
            next() {
                if (this.currentSlideIndex < this.slides.length) {
                    this.currentSlideIndex = this.currentSlideIndex + 1
                } else {
                    // Si es la última diapositiva, ve a la primera diapositiva
                    this.currentSlideIndex = 1
                }
            },
            autoplay() {
                this.autoplayInterval = setInterval(() => {
                    if (! this.isPaused) {
                        this.next()
                    }
                }, this.autoplayIntervalTime)
            },
            // Tiempo de intervalo de actualizaciones
            setAutoplayInterval(newIntervalTime) {
                clearInterval(this.autoplayInterval)
                this.autoplayIntervalTime = newIntervalTime
                this.autoplay()
            },
        }" x-init="autoplay" class="relative w-full overflow-hidden">
             @if(!$withoutArrows)
                <!-- previous button -->
                <button type="button" class="cursor-pointer p-2 flex justify-center content-center items-center absolute left-5 top-1/2 z-20  rounded-full -translate-y-1/2 bg-surface/40 text-on-surface transition hover:bg-surface/60 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:outline-offset-0 dark:bg-surface-dark/40 dark:text-on-surface-dark dark:hover:bg-surface-dark/60 dark:focus-visible:outline-primary-dark" aria-label="previous slide" x-on:click="previous()">
                    <span class="icon-[icon-park--left] size-5 md:size-6"></span>
                </button>
                <!-- next button -->
                <button type="button" class="cursor-pointer absolute right-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-surface/40 p-2 text-on-surface transition hover:bg-surface/60 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:outline-offset-0 dark:bg-surface-dark/40 dark:text-on-surface-dark dark:hover:bg-surface-dark/60 dark:focus-visible:outline-primary-dark" aria-label="next slide" x-on:click="next()">
                    <span class="icon-[icon-park--right] size-5 md:size-6"></span>
                </button>
             @endif
             <!-- slides -->
            <div {{ $attributes->class(["relative min-h-[50svh]  w-full rounded overflow-hidden"]) }}>
                <template x-for="(slide, index) in slides">
                    <div x-cloak x-show="currentSlideIndex == index + 1" class="absolute inset-0" x-transition.opacity.duration.1000ms>
                            <!-- Title and description -->
                            <div class="lg:px-32 lg:py-14 absolute inset-0 z-10 flex flex-col items-center justify-end gap-2 bg-linear-to-t from-surface-dark/85 to-transparent px-16 py-12 text-center">
                                <h3 class="w-full lg:w-[80%] text-balance text-2xl lg:text-3xl font-bold text-on-surface-dark-strong" x-text="slide.title" x-bind:aria-describedby="'slide' + (index + 1) + 'Description'"></h3>
                                <p class="lg:w-1/2 w-full text-pretty text-sm text-on-surface-dark" x-text="slide.description" x-bind:id="'slide' + (index + 1) + 'Description'"></p>
                                <template x-if="slide.ctaUrl">
                                    <a :href="slide.ctaUrl">
                                        <x-button type="button" x-cloak x-text="slide.ctaText" variant="outline" color="info"/>
                                    </a>
                                </template>
                                <template x-if="!slide.ctaUrl && slide.ctaText">
                                    <x-button type="button" x-cloak x-text="slide.ctaText" variant="outline" color="info"/>
                                </template>
                            </div>
                        <img class="absolute w-full h-full inset-0 object-cover text-on-surface dark:text-on-surface-dark" x-bind:src="slide.imgSrc" x-bind:alt="slide.imgAlt" />
                    </div>
                </template>
            </div>
                <!-- Pause/Play Button -->
             @if($withoutPlay)
                <button type="button" class="absolute bottom-5 right-5 z-20 p-1 text-center flex justify-center items-center content-center rounded-full cursor-pointer bg-on-surface-dark opacity-50 transition hover:opacity-80 focus-visible:opacity-80 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-dark active:outline-offset-0" aria-label="pause carousel" x-on:click="(isPaused = !isPaused), setAutoplayInterval(autoplayIntervalTime)" x-bind:aria-pressed="isPaused">
                    <span x-cloak x-show="isPaused" class="icon-[line-md--play] size-7 p-0 m-0"></span>
                    <span x-cloak x-show="!isPaused" class="icon-[line-md--pause] size-7 p-0 m-0"></span>
                </button>
             @endif
                <!-- indicators -->
            @if(! $withoutIndicators)
                <div class="absolute rounded-radius bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-4 md:gap-3 px-1.5 py-1 md:px-2" role="group" aria-label="slides" >
                    <template x-for="(slide, index) in slides">
                        <button class="size-2 rounded-full transition cursor-pointer" x-on:click="(currentSlideIndex = index + 1), setAutoplayInterval(autoplayIntervalTime)" x-bind:class="[currentSlideIndex === index + 1 ? 'bg-on-surface-dark' : 'bg-on-surface-dark/50']" x-bind:aria-label="'slide ' + (index + 1)"></button>
                    </template>
                </div>
            @endif
        </div>
BLADE;

    }
}