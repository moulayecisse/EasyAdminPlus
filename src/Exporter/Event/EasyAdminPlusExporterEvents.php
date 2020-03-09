<?php

namespace Cisse\EasyAdminPlusBundle\Exporter\Event;

final class EasyAdminPlusExporterEvents
{
    // Events related to backend views
    /** @Event("Symfony\Component\EventDispatcher\GenericEvent") */
    const PRE_EXPORT = 'cisse.easy_admin_plus.pre_export';
    /** @Event("Symfony\Component\EventDispatcher\GenericEvent") */
    const POST_EXPORT = 'cisse.easy_admin_plus.post_export';
}
