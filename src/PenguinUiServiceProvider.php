<?php

namespace PenguinUi;

use Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use PenguinUi\View\Components\Accordion;
use PenguinUi\View\Components\Alert;
use PenguinUi\View\Components\Avatar;
use PenguinUi\View\Components\Badge;
use PenguinUi\View\Components\Breadcrumbs;
use PenguinUi\View\Components\Button;
use PenguinUi\View\Components\Calendar;
use PenguinUi\View\Components\Carousel;
use PenguinUi\View\Components\Collapse;
use PenguinUI\Console\Commands\PenguinInstallCommand;
use PenguinUi\PenguinUi;

class PenguinUiServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        $this->registerComponents();
        $this->registerBladeDirectives();

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }
    public function registerComponents() {

        // Just rename <x-icon> provided by BladeUI Icons to <x-svg> to not collide with ours
        Blade::component('BladeUI\Icons\Components\Icon', 'svg');

        $prefix = config('penguin-ui.prefix');

        Blade::component($prefix . 'accordion', Accordion::class);
        Blade::component($prefix . 'accordion-item', Collapse::class);
        Blade::component($prefix . 'alert', Alert::class);
        Blade::component($prefix . 'avatar', Avatar::class);
        Blade::component($prefix . 'badge', Badge::class);
        Blade::component($prefix . 'breadcrumbs', Breadcrumbs::class);
        Blade::component($prefix . 'button', Button::class);
        Blade::component($prefix . 'calendar', Calendar::class);
        Blade::component($prefix . 'carousel', Carousel::class);


    }
    public function registerBladeDirectives(): void
    {
        $this->registerScopeDirective();
    }

    public function registerScopeDirective(): void
    {
        /**
         * All credits from this blade directive goes to Konrad Kalemba.
         * Just copied and modified for my very specific use case.
         *
         * https://github.com/konradkalemba/blade-components-scoped-slots
         */
        Blade::directive('scope', function ($expression) {
            // Split the expression by `top-level` commas (not in parentheses)
            $directiveArguments = preg_split("/,(?![^\(\(]*[\)\)])/", $expression);
            $directiveArguments = array_map('trim', $directiveArguments);

            [$name, $functionArguments] = $directiveArguments;

            // Build function "uses" to inject extra external variables
            $uses = Arr::except(array_flip($directiveArguments), [$name, $functionArguments]);
            $uses = array_flip($uses);
            array_push($uses, '$__env');
            array_push($uses, '$__bladeCompiler');
            $uses = implode(',', $uses);

            /**
             *  Slot names can`t contains dot , eg: `user.city`.
             *  So we convert `user.city` to `user___city`
             *
             *  Later, on component it will be replaced back.
             */
            $name = str_replace('.', '___', $name);

            return "<?php \$__bladeCompiler = \$__bladeCompiler ?? null; \$loop = null; \$__env->slot({$name}, function({$functionArguments}) use ({$uses}) { \$loop = (object) \$__env->getLoopStack()[0] ?>";
        });

        Blade::directive('endscope', function () {
            return '<?php }); ?>';
        });
    }
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/penguin-ui.php', 'penguin');

        // Register the service the package provides.
        $this->app->singleton('penguin', function ($app) {
            return new PenguinUi();
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['penguin'];
    }
    /**
     * Console-specific booting.
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/penguin-ui.php.php' => config_path('penguin-ui.php'),
        ], 'penguin.config');

        $this->commands([PenguinInstallCommand::class]);

//        $this->commands([MaryInstallCommand::class, MaryBootcampCommand::class]);
    }
}