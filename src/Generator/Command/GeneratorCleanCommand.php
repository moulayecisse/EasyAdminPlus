<?php

namespace Cisse\EasyAdminPlusBundle\Generator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GeneratorCleanCommand extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, string $name = null)
    {
        parent::__construct($name);
        $this->container = $container;
    }

    protected function configure(): void
    {
        $this
            ->setName('cisse:easy-admin-plus:generator:cleanup')
            ->setDescription('Cleans easy admin configuration files for non-existing entities.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $container = $this->container;
        $dirProject = $container->getParameter('kernel.project_dir');

        if (!is_dir($dirProject.'/config/packages/easy_admin')) {
            throw new \RuntimeException('Unable to clean easy admin configuration, no configuration file found.');
        }

        $eaTool = $container->get('cisse.easy_admin_plus.generator.clean');
        $eaTool->run();
    }
}
