<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\ReadOnlyAccessBundle\Entity;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="kimai2_plugin_readonlyaccesscustomerpam")
 * @ORM\Entity()
 */
class ReadOnlyAccessCustomerMap 
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var Customer
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Customer")
     */
    private $customer;


    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     */
    private $user;



    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUser()."=>".$this->getCustomer();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
