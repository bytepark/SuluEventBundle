<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Command;

use Sulu\Bundle\EventBundle\Event\EventManagerInterface;
use Massive\Bundle\SearchBundle\Search\SearchManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ReindexCommand
 *
 * @package    Sulu\Bundle\EventBundle\Command
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class ReindexCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('sulu:search:reindex-events');
        $this->setDescription('Reindex the events in the search index');
        $this->setHelp(
            <<<EOT
            The %command.name_full% command will reindex all the events in search index.
EOT
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        /** @var EventManagerInterface $eventManager */
        $eventManager = $container->get('sulu_event.event_manager');

        /** @var SearchManagerInterface $searchManager */
        $searchManager = $container->get('massive_search.search_manager');

        $results = $eventManager->findAll();

        foreach ($results as $event) {
            try {
                $output->writeln(
                    '  [+] <comment>Indexing published event</comment>: ' . $event->getTitle()
                );
                $searchManager->index($event);
            } catch (\Exception $exc) {
                $output->writeln(
                    '  [!] <error>Error indexing or de-indexing event (path: ' . $event->getTitle() .
                    ': ' . $exc->getMessage() . '</error>'
                );
            }
        }
    }
}
