<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright      {@link http://xoops.org/ XOOPS Project}
 * @license        {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */

use Xmf\Module\Helper;

require_once __DIR__ . '/../../mainfile.php';

// We verify that the user can post comments **********************************
if (!isset($GLOBALS['xoopsModuleConfig'])) {
    die();
}

if ($moduleHelper->getConfig(['com_rule'] == 0)) {    // Comments are deactivate
    die();
}

if ($moduleHelper->getConfig(['com_anonpost'] == 0 && !is_object($xoopsUser))) {    // Anonymous users can't post
    die();
}
// ****************************************************************************
include_once XOOPS_ROOT_PATH . '/include/comment_post.php';
