<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
class FooCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('demo:command');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Hyperf Demo Command');
    }

    public function handle()
    {
        go(function (){
            while(1){
                sleep(1);
                $this->line(1);
            }
        });


        go(function (){
            while(1){
                sleep(1);
                $this->line(2);
            }
        });
        go(function (){
            while(1){
                sleep(1);
                $this->line(3);
            }
        });


        go(function (){
            while(1){
                sleep(1);
                $this->line(4);
            }
        });

        $this->line('Hello Hyperf!', 'info');
    }
}
