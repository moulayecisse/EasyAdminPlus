<?php

namespace Cisse\EasyAdminPlusBundle\Generator\Command;

use Cisse\EasyAdminPlusBundle\Generator\Exception\RuntimeCommandException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;

class GeneratorEntityCommand extends Command
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
            ->setName('cisse:easy-admin-plus:generator:entity')
            ->setDescription('Create a specified entity file configuration for easy admin')
            ->setDefinition(
                new InputDefinition(array(
                    new InputOption('force', 'f'),
                ))
            )
            ->addArgument('entity', InputArgument::IS_ARRAY, 'The entity name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $dirProject = $this->container->getParameter('kernel.project_dir');
        $entiyManager = $this->container->get('doctrine.orm.entity_manager');
        $helper = $this->getHelper('question');
        $entitiesRawName = $input->getArgument('entity');
        $entitiesMetaData = [];

        if (!is_dir($dirProject.'/config/packages/easy_admin/')) {
            $output->writeln('You need to launch <info>cisse:easy-admin-plus:generator:generate</info> command before launching this command.');

            return;
        }

        foreach ($entitiesRawName as $entityRawName) {
            $entitySplit = explode(':', $entityRawName);

            if (1 == count($entitySplit) && isset($entitySplit[0]) && $entitySplit[0] == $entityRawName) {
                $entitySplit = ['App', $entityRawName];
            } elseif (empty($entitySplit) || in_array($entityRawName, $entitySplit) || 2 != count($entitySplit)) {
                $output->writeln('<comment>You have to enter a valid entity name prefixed by the name of the bundle to which it belongs (ex: AppBundle:Image), '.$entityRawName.' is invalid <info>
the generation process is stopped</info></comment>');

                return;
            }

            $entitiesMetaData[] = $entiyManager->getClassMetadata($entitySplit[0].'\Entity\\'.$entitySplit[1]);
        }

        if (!$input->getOption('force')) {
            foreach ($entitiesMetaData as $entity) {
                if (file_exists($dirProject.'/config/packages/easy_admin/entities/'.$entity.'.yaml')) {
                    $question = new ConfirmationQuestion(sprintf('A easy admin config file for %s, already exist, do you want to override it [<info>y</info>/n]?', $entity), true);
                    if (!$helper->ask($input, $output, $question)) {
                        return;
                    }
                }
            }
        }

        try {
            $eaTool = $this->container->get('cisse.easy_admin_plus.generator.entity');
            $eaTool->run($entitiesMetaData, $this);
        } catch (RuntimeCommandException $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
        }
    }
}
