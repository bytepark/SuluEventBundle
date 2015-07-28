<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\DataFixtures\Events;

use Bytepark\Csv\CsvIterator;
use Bytepark\Geocoder\GoogleMaps;
use Bytepark\HttpAdapter\GuzzleHttpAdapter;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sulu\Bundle\EventBundle\Entity\Event;
use Sulu\Bundle\EventBundle\Entity\EventOrganizer;
use Locale;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Intl\Intl;

/**
 * LoadEvents
 *
 * @package    Sulu\Bundle\EventBundle\DataFixtures\Events
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class LoadEvents implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $objectManager
     */
    public function load(ObjectManager $objectManager)
    {
        $csvIterator = new CsvIterator($this->container->getParameter('sulu_event.csv_import_file'));

        \Locale::setDefault('de');
        $countries = Intl::getRegionBundle()->getCountryNames();

        $geocoder = new GoogleMaps(new GuzzleHttpAdapter(), true, $this->container->getParameter('sulu_event.google_maps_api_key'));

        $output = $this->getConsoleOutput();

        foreach ($csvIterator as $row => $data) {
            if (empty($data)) {
                break;
            }

            if ($row == 1) {
                continue;
            }

            // sleep 1 second to prevent Google Geocode API from going OVER_QUERY_LIMIT
            sleep(1);

            $event = new Event();

            $event->setTitle(trim($data[0]) === '' ? 'Nicht definierter Event-Titel' : $data[0]);
            $event->setStartDate(new \DateTime($data[1]));

            if (!empty($data[2])) {
                $event->setStartTime(new \DateTime($data[2]));
            }

            $event->setDescription('<p>' . $data[3] . '</p>');

            $event->setDescriptionVenue('<p>' . $data[4] . '</p>');
            $event->setZip($data[5]);
            $event->setCity($data[6]);

            $countryAlpha2 = array_keys($countries, $data[7]);

            $event->setCountry(isset($countryAlpha2[0]) ? $countryAlpha2[0] : 'DE');


            $organizer = new EventOrganizer();
            $organizer->setTitle($data[10]);
            $organizer->setFirstName($data[11]);
            $organizer->setLastName($data[12]);
            $organizer->setStreet($data[13]);
            $organizer->setZip($data[14]);
            $organizer->setCity($data[15]);
            $organizer->setPhone($data[16]);
            $organizer->setFax($data[17]);
            $organizer->setEmail($data[18]);

            $event->setOrganizer($organizer);

            $result = $geocoder->geocode($data[4] . '+'. $data[5]);

            if (isset($result->results[0])) {
                $event->setLatitude($result->results[0]->geometry->location->lat);
                $event->setLongitude($result->results[0]->geometry->location->lng);
            } else {
                $error = 'No geocoding data at event ' . $data[0]. '(row ' . $row . ') with status' . $result->status;
                $output->writeln($error);
                $event->setLatitude(0);
                $event->setLongitude(0);
            }

            $event->setWebsite($data[19]);

            $objectManager->persist($event);
        }

        $objectManager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }

    /**
     * @return ConsoleOutputInterface
     */
    private function getConsoleOutput()
    {
        return new ConsoleOutput();
    }
}
