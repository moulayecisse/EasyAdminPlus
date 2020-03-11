<?php

namespace Cisse\EasyAdminPlusBundle\Auth\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Cisse\EasyAdminPlusBundle\Entity\User;
use Cisse\EasyAdminPlusBundle\Auth\Event\EasyAdminPlusAuthEvents;

class UserRemoveCommand extends Command
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

    protected function configure()
    {
        $this
            ->setName('cisse:easy-admin-plus:user:remove')
            ->setDescription('Enable an admin')
            ->setDefinition(
                [
                    new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                ]
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->container;
        $em = $container->get('doctrine')->getManager();
        $dispatcher = $container->get('event_dispatcher');

        $username = $input->getArgument('username');

        /** @var User $user */
        if (null === ($user = $em->getRepository(User::class)->findOneByUsername($username))) {
            $output->writeln(sprintf('<error>User %s was not found</error>', $username));
        }

        $dispatcher->dispatch(EasyAdminPlusAuthEvents::USER_PRE_REMOVE, new GenericEvent($user));

        $em->remove($user);
        $em->flush();

        $dispatcher->dispatch(EasyAdminPlusAuthEvents::USER_POST_REMOVE, new GenericEvent($user));

        $output->writeln(sprintf('User <comment>%s</comment> removed', $username));
    }
}
