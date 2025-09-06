<?php

namespace PenguinUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{

    public string $uuid;
    public string $class;
    public function __construct(
        public ?string $id = null,
        public ?string $value = null,
        public ?string $color = null,

    ) {
        $this->uuid = "penguin-" . md5(serialize($this)) . $id;
        $this->setColorClass();
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <span  {{ $attributes->class([$class])}}>
                <span class="px-2 py-1 bg-surface-alt/10 dark:bg-surface-dark-alt/10">{{ $value }}</span>
            </span>
            HTML;
    }
    public function setColorClass()
    {
        $clasess = '';
        switch ($this->color) {
            default : $clasess = 'badge'; break;
            case 'inverse': $clasess = 'badge-inverse'; break;
            case 'primary': $clasess = 'badge-primary'; break;
            case 'secondary': $clasess = 'badge-secondary'; break;
            case 'info': $clasess = 'badge-info'; break;
            case 'success': $clasess = 'badge-success'; break;
            case 'warning': $clasess = 'badge-warning'; break;
            case 'danger': $clasess = 'badge-danger'; break;
        }
        $this->class = $clasess;
    }
}