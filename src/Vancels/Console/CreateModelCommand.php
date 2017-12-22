<?php

namespace Vancels\Tools\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class CreateModelCommand
 * @package Vancels\Administrator\Console
 */
class CreateModelCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'vancels:create_model';
    protected $filename = '.phpstorm.meta.php';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Model for PhpStorm';

    /** @var \Illuminate\Contracts\Filesystem\Filesystem */
    protected $files;

    /** @var \Illuminate\Contracts\View\Factory */
    protected $view;

    protected $methods = [
        'new \Illuminate\Contracts\Container\Container',
        '\Illuminate\Contracts\Container\Container::make(\'\')',
        '\App::make(\'\')',
        '\app(\'\')',
    ];

    /**
     *
     * @param \Illuminate\Contracts\Filesystem\Filesystem $files
     * @param \Illuminate\Contracts\View\Factory          $view
     */
    public function __construct($files, $view)
    {
        $this->files = $files;
        $this->view  = $view;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $name = $this->argument('name');

        $ask    = <<<eot
Please Input Action
- 1 create model
- 2 set edit list
eot;
        $action = $this->ask($ask);
        switch ($action) {
            case 1:
                $fileName = base_path("{$name}.txt");
                $content  = file_get_contents($fileName);
                $line     = explode("\r\n", $content);
                \Schema::create($name, function (\Illuminate\Database\Schema\Blueprint $table) use ($line) {
                    foreach ($line as $value) {
                        $table->string($value)->nullable();
                    }
                    $table->timestamps();
                });
                break;
            case 2:
                $fileName = base_path("{$name}.txt");
                $content  = file_get_contents($fileName);
                $line     = explode("\r\n", $content);
                $out      = [];
                foreach ($line as $value) {
                    $out[$value] = array(
                        'title' => $value,
                        'type'  => 'text',
                    );
                }

                $out = var_export($out, 1);
                file_put_contents(base_path("{$name}.edit.txt"), $out);
                break;
        }


        $this->info("Success");
    }

    /**
     * Get a list of abstracts from the Laravel Application.
     *
     * @return array
     */
    protected function getAbstracts()
    {
        $abstracts = $this->laravel->getBindings();

        // Return the abstract names only
        return array_keys($abstracts);
    }

    /**
     * Register an autoloader the throws exceptions when a class is not found.
     */
    protected function registerClassAutoloadExceptions()
    {
        spl_autoload_register(function ($class) {
            throw new \Exception("Class '$class' not found.");
        });
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'The Name'),
        );
    }
}
