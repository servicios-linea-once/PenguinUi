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
use PenguinUi\View\Components\Chart;
use PenguinUi\View\Components\Checkbox;
use PenguinUi\View\Components\Collapse;
use PenguinUI\Console\Commands\PenguinInstallCommand;
use PenguinUi\PenguinUi;
use PenguinUi\View\Components\DatePicker;
use PenguinUi\View\Components\Drawer;
use PenguinUi\View\Components\Dropdown;
use PenguinUi\View\Components\DropdownItem;
use PenguinUi\View\Components\Editor;
use PenguinUi\View\Components\Errors;
use PenguinUi\View\Components\File;
use PenguinUi\View\Components\FilePond;
use PenguinUi\View\Components\Input;
use PenguinUi\View\Components\ListItem;
use PenguinUi\View\Components\menu\Menu;
use PenguinUi\View\Components\menu\MenuItem;
use PenguinUi\View\Components\menu\MenuSeparador;
use PenguinUi\View\Components\menu\MenuSub;
use PenguinUi\View\Components\Model;
use PenguinUi\View\Components\Nav\Nav;
use PenguinUi\View\Components\Nav\NavItem;
use PenguinUi\View\Components\Password;
use PenguinUi\View\Components\Select;

class PenguinUiServiceProvider extends ServiceProvider
{
    /**
     * Realice el arranque de servicios posterior al registro.
     */
    public function boot(): void
    {
        $this->registerComponents();
        $this->registerBladeDirectives();

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // La publicación solo es necesaria cuando se usa la CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }
    public function registerComponents() {

        // Solo cambiar el nombre <x-icon> proporcionado por iconos de Bladeui a <x-svg> no chocar con el nuestro
//        Blade::component('BladeUI\Icons\Components\Icon', 'svg');

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
        Blade::component($prefix . 'chart', Chart::class);
        Blade::component($prefix . 'checkbox', Checkbox::class);
        Blade::component($prefix . 'select', Select::class);
        Blade::component($prefix . 'datepicker', DatePicker::class);
        Blade::component($prefix . 'drawer', Drawer::class);
        Blade::component($prefix . 'dropdown', Dropdown::class);
        Blade::component($prefix . 'dropdown-item', DropdownItem::class);
        Blade::component($prefix . 'editor-jodit', Editor::class);
        Blade::component($prefix . 'errors', Errors::class);
        Blade::component($prefix . 'file', File::class);
        Blade::component($prefix . 'file-pond', FilePond::class);
        Blade::component($prefix . 'input', Input::class);
        Blade::component($prefix . 'password', Password::class);
        Blade::component($prefix . 'list-item', ListItem::class);
        Blade::component($prefix . 'menu', Menu::class);
        Blade::component($prefix . 'menu-item', MenuItem::class);
        Blade::component($prefix . 'menu-sub', MenuSub::class);
        Blade::component($prefix . 'menu-separador', MenuSeparador::class);
        Blade::component($prefix . 'model', Model::class);
        Blade::component($prefix . 'nav', Nav::class);
        Blade::component($prefix . 'nav-item', NavItem::class);


    }
    public function registerBladeDirectives(): void
    {
        $this->registerScopeDirective();
    }

    public function registerScopeDirective(): void
    {
        /**
         * Todos los créditos de esta Directiva de Blade van a Konrad Kalemba.
         * Acabo de copiar y modificar para mi caso de uso muy específico.
         *
         * https://github.com/konradkalemba/blade-components-scoped-slots
         */
        Blade::directive('scope', function ($expression) {
            // Dividir la expresión por las comas `de nivel superior '(no entre paréntesis)
            $directiveArguments = preg_split("/,(?![^\(\(]*[\)\)])/", $expression);
            $directiveArguments = array_map('trim', $directiveArguments);

            [$name, $functionArguments] = $directiveArguments;

            // Función de construcción "uses" para inyectar variables externas adicionales
            $uses = Arr::except(array_flip($directiveArguments), [$name, $functionArguments]);
            $uses = array_flip($uses);
            array_push($uses, '$__env');
            array_push($uses, '$__bladeCompiler');
            $uses = implode(',', $uses);

            /**
             *  Los nombres de la ranura no pueden contener punto, eg: `user.city`.
             *  Entonces convertimos `user.city` a `user___city`
             *
             *  Más tarde, en el componente se reemplazará.
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

        // Registre el servicio que proporciona el paquete.
        $this->app->singleton('penguin', function ($app) {
            return new PenguinUi();
        });
    }
    /**
     * Obtenga los servicios proporcionados por el proveedor.
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
        // Publicar el archivo de configuración.
        $this->publishes([
            __DIR__ . '/../config/penguin-ui.php.php' => config_path('penguin-ui.php'),
        ], 'penguin.config');

        $this->commands([PenguinInstallCommand::class]);
    }
}