<?php

namespace Aleahy\ArtisanVueMake;

use Aleahy\ArtisanVueMake\Exceptions\ComponentAlreadyExistsException;
use Aleahy\ArtisanVueMake\Exceptions\TagAlreadyExistsException;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeVueComponent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:vue 
                            {path : The path to the component using dot-notation from the components directory} 
                            {--tag= : The tag to be used in app.js for the component}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a vue component';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            if ($this->componentExists()) {
                throw new ComponentAlreadyExistsException();
            }

            if ($this->checkIfTagExists()) {
                throw new TagAlreadyExistsException($this->option('tag'));
            }

            $this->createComponentFile();

            $this->newLine();
            $this->info('Component File created');

            if ($this->option('tag')) {
                $this->updateAppJS();

                $this->newLine();
                $this->info($this->option('tag') . ' inserted into app.js');

            }

            $this->newLine();

            return 0;
        }

        catch(\Exception $e) {
            $this->newLine();
            $this->error($e->getMessage());
            $this->newLine();
            return 1;
        }
    }

    protected function checkIfTagExists(): bool
    {
        if (!$this->option('tag')) {
            return false;
        }

        return Str::contains(
            file_get_contents(resource_path('/js/app.js')),
            $this->option('tag')
        );
    }

    protected function updateAppJS(): void
    {
        $currentContents = file_get_contents(resource_path('/js/app.js'));

        $pos = stripos($currentContents, "\n", strrpos($currentContents, 'Vue.component('));

        $newContents = substr_replace($currentContents, $this->makeEntry(), $pos+1, 0);

        file_put_contents(resource_path('/js/app.js'), $newContents);
    }

    protected function makeEntry(): string
    {
        $pathToComponent = './components/' . $this->getComponentQualifiedName();

        $tag = $this->option('tag');

        return "Vue.component('$tag', require('$pathToComponent').default);\n";
    }

    protected function getComponentQualifiedName(): string
    {
        return Str::replace('.', '/', $this->argument('path'));
    }

    protected function getResourceComponentPath(): string
    {
        return resource_path('js/components/' . $this->getComponentQualifiedName() . '.vue');
    }

    protected function componentExists(): bool
    {
        $path = $this->getResourceComponentPath();
        return file_exists($path);
    }

    protected function createComponentFile(): void
    {
        $path = $this->getResourceComponentPath();

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $comp = file_get_contents($this->getTemplateStub());

        file_put_contents($path, $comp);
    }

    protected function getTemplateStub() {
        return __DIR__ . '/stubs/VueComponent.vue.stub';
    }
}
