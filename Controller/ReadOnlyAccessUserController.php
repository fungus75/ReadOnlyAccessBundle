<?php

/*
 * This file is part of the Kimai ReadOnlyAccessBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\ReadOnlyAccessBundle\Controller;

use App\Controller\AbstractController;
use App\Repository\Query\UserQuery;
use App\Repository\Query\CustomerQuery;
use App\Entity\User;
use App\Entity\Customer;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/readonly-access-user")
 * @Security("is_granted('view_readonly_customer')")
 */
class ReadOnlyAccessUserController extends AbstractController
{
    /**
     * @Route(path="/showCustomer", name="readonly_access_showcustomer", methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCustomerTimesAction(Request $request) 
    {
	$customer="xx";
	return $this->render('@ReadOnlyAccess/showCustomerTimes.html.twig', [
	    'customer' => $customer
        ]);
    }

}
