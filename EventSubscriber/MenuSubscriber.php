<?php

/*
 * This file is part of the Kimai ReadOnlyAccessBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\ReadOnlyAccessBundle\EventSubscriber;

use App\Event\ConfigureMainMenuEvent;
use KevinPapst\AdminLTEBundle\Model\MenuItemModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;



class MenuSubscriber implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $security;

    private $tokenStorage;

    /**
     * MenuSubscriber constructor.
     * @param AuthorizationCheckerInterface $security
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $security)
    {
        $this->security = $security;
	$this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConfigureMainMenuEvent::CONFIGURE => ['onMenuConfigure', 100],
        ];
    }

    /**
     * @param \App\Event\ConfigureMainMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMainMenuEvent $event)
    {
        $auth = $this->security;

        if (!$auth->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return;
        }

        $menu = $event->getSystemMenu();

        if ($auth->isGranted('ROLE_SUPER_ADMIN') || $auth->isGranted('edit_readonly_user')) {
            $menu->addChild(
                new MenuItemModel('readonly_access', 'Readony User', 'readonly_access_admin', [], 'fas fa-book')
            );
        }


	if ($token=$this->tokenStorage->getToken()) {
		$user=$token->getUser();
		$customerId=$user->getPreferenceValue("readOnlyAccessCustomer");
		if ($customerId!="") {
	            $event->getMenu()->addItem(
        	        new MenuItemModel('readonly_access_showcustomer', 'Show Recorded Times', 'readonly_access_showcustomer', [], 'fas fa-book')
            	    );
	        } 
         }
    }
}
