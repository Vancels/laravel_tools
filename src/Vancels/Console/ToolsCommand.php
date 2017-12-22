<?php

namespace Vancels\Tools\Console;

use Illuminate\Console\Command;

/**
 * Class CreateModelCommand
 * @package Vancels\Administrator\Console
 */
class ToolsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'vancels:tools';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tools';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $ask = <<<EOT
Please Set Tools Config Like
- idea
EOT;

        $answer = $this->ask($ask);
        switch ($answer) {
            case "idea":

                $this->call('ide-helper:generate');
                $this->call('ide-helper:meta');
                $this->call('ide-helper:models');
                break;
        }

    }

}
