<?php
namespace PenguinUI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use RuntimeException;
use function Laravel\Prompts\select;
class PenguinInstallCommand extends Command
{
    protected $signature = 'penguin:install';

    protected $description = 'Descripción del comando';
    protected $ds = DIRECTORY_SEPARATOR;

    public function handle()
    {
        $this->info("️😎 Instalador de PenguinUI 😎");

        // Laravel 12+
        $this->checkForLaravelVersion();

        // Install Volt ?
        $shouldInstallVolt = $this->askForVolt();

        //Yarn or Npm or Bun or Pnpm ?
        $packageManagerCommand = $this->askForPackageInstaller();

        // Install Livewire/Volt
        $this->installLivewire($shouldInstallVolt);

        // Setup Tailwind and Daisy
        $this->setupTailwindPenguin($packageManagerCommand);

        // Copy stubs if is brand-new project
//        $this->copyStubs($shouldInstallVolt);

        // Rename components if Jetstream or Breeze are detected
//        $this->renameComponents();

        // Clear view cache
        Artisan::call('view:clear');

        $this->info("\n");
        $this->info("✅  ¡Está hecho!!");
        $this->info("❤️  Patrocinador: https://github.com/sponsors/kirinthor");
        $this->info("\n");
    }

    public function setupTailwindPenguin(string $packageManagerCommand)
    {
        /**
         * Install PenguinUI + Tailwind
         */
        $this->info("\nInstalación de PenguinUI + TailwindCss + Iconify ...\n");

        Process::run("$packageManagerCommand alpinejs @alpinejs/focus @alpinejs/mask @alpinejs/collapse tailwindcss @tailwindcss/vite @iconify/tailwind4 @iconify/json", function (string $type, string $output) {
            echo $output;
        })->throw();

        /**
         * Setup app.css
         */
        $cssPath = base_path() . "{$this->ds}resources{$this->ds}css{$this->ds}app.css";
        $css = File::get($cssPath);

        $penguin = <<<EOT
            \n
            /**
                Las líneas de arriba están intactas.
                Las líneas a continuación fueron agregadas por PenguinUi Installer.
            */
            \n
            /* penguinUI */
            @plugin "@iconify/tailwind4";
            @source "../../vendor/kirinthor/penguin-ui/src/View/Components/**/*.php";
            @import "../../vendor/kirinthor/penguin-ui/resources/sass/app.css";
            /** theme UI */
             @theme {
                /* light theme */
                --color-surface: var(--color-white);
                --color-surface-alt: var(--color-neutral-50);
                --color-on-surface: var(--color-neutral-500);
                --color-on-surface-strong: var(--color-neutral-900);
                --color-primary: var(--color-indigo-600);
                --color-on-primary: var(--color-neutral-100);
                --color-secondary: var(--color-cyan-500);
                --color-on-secondary: var(--color-white);
                --color-outline: var(--color-stone-400);
                --color-outline-strong: var(--color-neutral-800);
            
                /* dark theme */
                --color-surface-dark: var(--color-neutral-800);
                --color-surface-dark-alt: var(--color-neutral-900);
                --color-on-surface-dark: var(--color-neutral-300);
                --color-on-surface-dark-strong: var(--color-white);
                --color-primary-dark: var(--color-indigo-600);
                --color-on-primary-dark: var(--color-white);
                --color-secondary-dark: var(--color-cyan-500);
                --color-on-secondary-dark: var(--color-white);
                --color-outline-dark: var(--color-neutral-600);
                --color-outline-dark-strong: var(--color-neutral-300);
            
                /* shared colors */
                --color-info: var(--color-blue-500);
                --color-on-info: var(--color-white);
                --color-success: var(--color-green-500);
                --color-on-success: var(--color-white);
                --color-warning: var(--color-yellow-500);
                --color-on-warning: var(--color-white);
                --color-danger: var(--color-red-500);
                --color-on-danger: var(--color-white);
            
                /* border radius */
                --radius-radius: var(--radius-sm);
            }  
            /* Theme toggle */
            @custom-variant dark (&:where(.dark, .dark *));
            EOT;

        $css = str($css)->append($penguin);

        File::put($cssPath, $css);

    }
    public function installLivewire(string $shouldInstallVolt)
    {
        $this->info("\nInstalando Livewire...\n");

        $extra = $shouldInstallVolt == 'Yes'
            ? ' livewire/volt && php artisan volt:install'
            : '';

        Process::run("composer require livewire/livewire $extra", function (string $type, string $output) {
            echo $output;
        })->throw();
    }
    public function askForPackageInstaller(): string
    {
        return  select(
            label: 'Instalar con ...',
            options: [
                'yarn add -D' => 'yarn',
                'npm install --save-dev' => 'npm',
                'bun i -D' => 'bun',
                'pnpm i -D' => 'pnpm'
            ],
            default: 'npm install --save-dev'
        );
    }
    /**
     * Also install Volt?
     */
    public function askForVolt(): string
    {
        return select(
            label: 'Instalar también `livewire/volt` ?',
            options: ['Yes', 'No'],
            default: '1',
            hint: 'No importa cuál sea su elección, siempre se instala `livewire/livewire`');
    }

    public function checkForLaravelVersion(): void
    {
        $this->info("\nVerificando Compatibilidad ...\n");

        if (version_compare(app()->version(), '12.0', '<')) {
            $this->error("❌ Se require Laravel 12 o superior.");
            exit;
        }
    }
    private function createDirectoryIfNotExists(string $path): void
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }
    private function copyFile(string $source, string $destination): void
    {
        $source = str_replace('/', DIRECTORY_SEPARATOR, $source);
        $destination = str_replace('/', DIRECTORY_SEPARATOR, $destination);

        if (!copy($source, $destination)) {
            throw new RuntimeException("No se pudo copiar {$source} a {$destination}");

        }
    }
}