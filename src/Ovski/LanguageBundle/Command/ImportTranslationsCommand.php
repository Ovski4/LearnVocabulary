<?php

namespace Ovski\LanguageBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportTranslationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ovski:translations:import')
            ->setDescription('Import translation from a csv file')
            ->addArgument('username', InputArgument::REQUIRED, 'The user username who will get the translations')
            ->addArgument('learning', InputArgument::REQUIRED, 'The learning to be imported')
            ->addArgument('csv', InputArgument::REQUIRED, 'The csv file path')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $username = $input->getArgument('username');
        $user = $em->getRepository('OvskiUserBundle:User')->findOneBy(array('username' => $username));
        if (!$user) {
            throw new \Exception(sprintf("User %s could not be found", $username));
        }
        $learning = $input->getArgument('learning');
        $csv = $input->getArgument('csv');
        $this->getContainer()->get('translation_manager')->importCsv($user, $learning, $csv);

        $output->writeln('Done');
    }
}