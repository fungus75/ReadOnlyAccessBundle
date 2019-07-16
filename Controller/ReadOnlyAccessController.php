<?php

/*
 * This file is part of the Kimai ReadOnlyAccessBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\ReadOnlyAccessBundle\Controller;

use App\Controller\AbstractController;
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
     * @Route(path="", name="readonly_access", methods={"GET", "POST"})

     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
	return $this->render('@ReadOnlyAccess/index.html.twig');
    }

}
