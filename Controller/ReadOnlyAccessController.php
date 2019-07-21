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
use App\Entity\User;

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

	return $this->render('@ReadOnlyAccess/index.html.twig', [
            'entries' => $entries,
            'query' => $query
        ]);
    }

}
