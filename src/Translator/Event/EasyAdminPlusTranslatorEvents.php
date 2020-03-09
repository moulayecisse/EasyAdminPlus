<?php

namespace Cisse\EasyAdminPlusBundle\Translator\Event;

final class EasyAdminPlusTranslatorEvents
{
    // Events related to custom action
    /** @Event("Symfony\Component\EventDispatcher\GenericEvent") */
    const PRE_TRANSLATE = 'cisse.easy_admin_plus.pre_translate';
    /** @Event("Symfony\Component\EventDispatcher\GenericEvent") */
    const POST_TRANSLATE = 'cisse.easy_admin_plus.post_translate';
}
