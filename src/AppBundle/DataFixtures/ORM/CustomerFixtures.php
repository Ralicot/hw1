<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Address;

class CustomerFixtures extends AbstractDataFixture
{

    protected function createAndPersistData()
    {
        $index = 0;
        $countries = $this->getReferences('country');
        foreach ($this->getReferences('account') as $account) {
            $index++;
            $contact = $this->getReference(sprintf('contact_%s', $index));
            $customer = new Customer();
            $customer->setAccount($account)->setContact($contact);
            $address1 = $this->createAddress($countries[array_rand($countries)]);
            $address2 = $this->createAddress($countries[array_rand($countries)]);
            $customer->addAddress($address1)->addAddress($address2);
            $this->manager->persist($address1);
            $this->manager->persist($address2);
            $this->manager->persist($customer);
        }
    }

    public function getOrder()
    {
        return 2;
    }
    public function createAddress($country)
    {
        $address = new Address();
        $address->setCountry($country);
        return $address;

    }
}
