<?php

namespace PenguinUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Calendar extends Component
{

    public string $uuid;
    /**
     * Create a new component instance.
     * <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
     * $events = [["id"=> 'a','title' => 'my event','start'=> '2025-09-05','end'=> '2025-09-10','color'=> 'red']]
     */
    public function __construct(
        public ?string $id = null,
        public ?string $locale = 'es',
        public ?string $timeZone = 'America/Lima',
        public ?string $initialView = 'dayGridMonth',
        public ?array $config = [],
        public ?array $events = [],
    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
    }
    public function setup(): string
    {
        return json_encode(
            array_merge([
                'initialView' => $this->initialView,
                'timeZone'=>$this->timeZone,
                'locale'=> $this->locale,
                'events' => $this->events,
            ],$this->config)
        );
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
<div  wire:key="calendar-{{ rand() }}" class="w-full">
    <div x-data x-init="const calendar = new FullCalendar.Calendar($el, {{ $setup() }}); calendar.render();" class="w-full"></div>
</div>
blade;
    }
}