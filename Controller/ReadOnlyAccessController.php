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

//use KimaiPlugin\CustomCSSBundle\Entity\CustomCss;
//use KimaiPlugin\CustomCSSBundle\Form\CustomCssType;
//use KimaiPlugin\CustomCSSBundle\Repository\CustomCssRepository;
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
	$customerQuery->setResultType(CustomerQuery::RESULT_TYPE_QUERYBUILDER);
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
            if ($userEntry->hasRole("READONLY_USER")) $userEntry->removeRole("READONLY_USER");
            else $userEntry->addRole("READONLY_USER");
	    $this->getDoctrine()->getManager()->flush();
        }

        return $this->indexAction($page,$request);
    }

}
