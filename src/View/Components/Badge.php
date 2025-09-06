<?php

namespace PenguinUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{

    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $value = null,

    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
                <div {{ $attributes->class(["badge"])}}>
                    {{ $value }}
                </div>
            HTML;
    }
}