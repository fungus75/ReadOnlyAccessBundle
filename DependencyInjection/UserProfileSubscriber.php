<?php

/*
 * This file is part of the Kimai ReadOnlyAccessBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\ReadOnlyAccessBundle\DependencyInjection;

use App\Entity\UserPreference;
use App\Form\Type\CustomerType;
use App\Event\UserPreferenceEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsNull;
use Symfony\Component\Validator\Constraints\NotNull;


class UserProfileSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserPreferenceEvent::CONFIGURE => ['loadUserPreferences', 200]
        ];
    }

    public function loadUserPreferences(UserPreferenceEvent $event)
    {
        if (null === ($user = $event->getUser())) {
            return;
        }

        // You attach every field to the event and all the heavy lifting is done by Kimai.
        // The value is the default as long as the user has not yet updated his preferences,
        // otherwise it will be overwritten with the users choice, stored in the database.
        $event->addUserPreference(
            (new UserPreference())
                ->setName('readOnlyAccessCustomer')
                ->setType(CustomerType::class)
                ->setEnabled(false)
        );
    }
} 

