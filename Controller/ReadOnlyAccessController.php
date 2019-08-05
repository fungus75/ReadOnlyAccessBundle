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
 * @Route(path="/admin/readonly-access")
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('edit_redonly_access')")
 */
class ReadOnlyAccessController extends AbstractController
{

    /**
     * @Route(path="", defaults={"page": 1}, name="readonly_access_admin", methods={"GET"})
     * @Route(path="/page/{page}", requirements={"page": "[1-9]\d*"}, name="readonly_access_admin_paginated", methods={"GET"})
     *
     * @param int $page
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page, Request $request)
    {
        $query = new UserQuery();
        $query->setPage($page);
        $query->setOrderBy('username');
        /* @var $entries Pagerfanta */
        $entries = $this->getDoctrine()->getRepository(User::class)->findByQuery($query);

	$customerQuery = new CustomerQuery();
        $customerQuery->setOrderBy('name');
	$customers = $this->getDoctrine()->getRepository(Customer::class)->findByQuery($customerQuery);


	return $this->render('@ReadOnlyAccess/index.html.twig', [
            'entries' => $entries,
            'query' => $query,
	    'page' => $page,
	    'customers' => $customers
        ]);
    }

   /**
     * @Route(path="/togglePermission/{page}/{user}", requirements={"page": "[1-9]\d*", "user": "[1-9]\d*"}, name="readonly_access_admin_togglepermission", methods={"GET"})
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('edit_readonly_user')")
     * @param int $page
     * @param int $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function togglePermissionAction($page, $user, Request $request)
    {
        $userEntry=$this->getDoctrine()->getRepository(User::class)->find($user);
        if ($userEntry!=null) {
            if ($userEntry->hasRole("ROLE_READONLYACCESS_USER")) $userEntry->removeRole("ROLE_READONLYACCESS_USER");
            else $userEntry->addRole("ROLE_READONLYACCESS_USER");
	    $this->getDoctrine()->getManager()->flush();
        }

        return $this->indexAction($page,$request);
    }


   /**
     * @Route(path="/changecustomer/{user}/{customer}", defaults={"customer": "", "user": ""}, requirements={"customer": "[1-9]\d*", "user": "[1-9]\d*"}, name="readonly_access_admin_changecustomer", methods={"GET"})
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('edit_readonly_user')")
     * @param int $user
     * @param int $customer
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeCustomerAction($user, $customer, Request $request)
    {
        $userEntry=$this->getDoctrine()->getRepository(User::class)->find($user);
        if ($userEntry!=null) {
            $userEntry->setPreferenceValue("readOnlyAccessCustomer",$customer);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->indexAction(1,$request);
    }


}
