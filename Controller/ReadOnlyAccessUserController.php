<?php

/*
 * This file is part of the Kimai ReadOnlyAccessBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\ReadOnlyAccessBundle\Controller;

use App\Controller\AbstractController;
use App\Repository\Query\ExportQuery;
use App\Entity\User;
use App\Entity\Customer;
use App\Entity\Timesheet;

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
     * @Route(path="/showTimeEntries/{monthOffset}", defaults={"monthOffset": "0"}, requirements={"monthOffset": "[1-9]\d*"}, name="readonly_access_showcustomer", methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCustomerTimesAction($monthOffset, Request $request) 
    {
	$customerId=$this->getUser()->getPreferenceValue("readOnlyAccessCustomer");
	$customer=$this->getDoctrine()->getRepository(Customer::class)->find($customerId);

	$begin=new \DateTime("first day of this month 00:00:00");

	$monthOffset+=0; //force monthOffset to be numeric
	if ($monthOffset>0) $begin->modify('-'.$monthOffset.' month');

	// end is allways the end of the "begin"-month
	$end=clone $begin;
	$end->modify('+1 month');
	$end->modify('-1 second'); // now it should be 23:59:59 on the last day of the month before
	

	$expQuery=new ExportQuery();
	$expQuery->setCustomer($customer);
	$expQuery->setOrder(ExportQuery::ORDER_ASC);
	$expQuery->setBegin($begin);
        $expQuery->setEnd($end);

	$timesheetRepository=$this->getDoctrine()->getRepository(Timesheet::class);
	$timesheets=$timesheetRepository->getTimesheetsForQuery($expQuery);
	

	return $this->render('@ReadOnlyAccess/showCustomerTimes.html.twig', [
	    'customer' => $customer,
            'entries' => $timesheets,
            'query'   => $expQuery,
	    'monthOffset' => $monthOffset
        ]);
    }

}
