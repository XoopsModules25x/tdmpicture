<?php
/**
 * ****************************************************************************
 *  - TDMSpot By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - Licence PRO Copyright (c)  (http://www.)
 *
 * Cette licence, contient des limitations
 *
 * 1. Vous devez posséder une permission d'exécuter le logiciel, pour n'importe quel usage.
 * 2. Vous ne devez pas l' étudier ni l'adapter à vos besoins,
 * 3. Vous ne devez le redistribuer ni en faire des copies,
 * 4. Vous n'avez pas la liberté de l'améliorer ni de rendre publiques les modifications
 *
 * @license     TDMFR GNU public license
 * @author      TDMFR ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */

if ((!defined('XOOPS_ROOT_PATH')) || !($GLOBALS['xoopsUser'] instanceof XoopsUser) || !$GLOBALS['xoopsUser']->IsAdmin()) {
    exit('Restricted access' . PHP_EOL);
}

/**
 * @param string $tablename
 *
 * @return bool
 */
function tableExists($tablename)
{
    $result = $GLOBALS['xoopsDB']->queryF("SHOW TABLES LIKE '$tablename'");

    return ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) ? true : false;
}

/**
 * @param $fieldname
 * @param $table
 * @return bool
 */
function FieldExists($fieldname, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW COLUMNS FROM $table LIKE '$fieldname'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 *
 * Prepares system prior to attempting to install module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_update_tdmpicture(XoopsModule $module)
{
    $moduleDirName = basename(dirname(__DIR__));
    $className     = ucfirst($moduleDirName) . 'Utilities';
    if (!class_exists($className)) {
        xoops_load('utilities', $moduleDirName);
    }
    //check for minimum XOOPS version
    if (!$className::checkXoopsVer($module)) {
        return false;
    }

    // check for minimum PHP version
    if (!$className::checkPHPVer($module)) {
        return false;
    }

    return true;
}

/**
 * @param XoopsModule|XoopsObject $xoopsModule
 * @param null|int                $previousVersion
 * @return bool
 */
function xoops_module_update_tdmpicture(XoopsModule $xoopsModule, $previousVersion = null)
{
    global $xoopsConfig, $xoopsDB, $xoopsUser;
    $moduleDirName = basename(dirname(__DIR__));

    if ($previousVersion < 106) {
        $xoopsDB->queryFromFile(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/sql/mysql1.06.sql');
    }

    if ($previousVersion < 108) {
        $xoopsDB->queryFromFile(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/sql/mysql1.07.sql');
    }
    if ($previousVersion < 109) {

        $configurator = include __DIR__ . '/config.php';
        $classUtilities = ucfirst($moduleDirName) . 'Utilities';
        if (!class_exists($classUtilities)) {
            xoops_load('utilities', $moduleDirName);
        }

        //delete old HTML templates
        if (count($configurator['templateFolders']) > 0) {
            foreach ($configurator['templateFolders'] as $folder) {
                $templateFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $folder);
                if (is_dir($templateFolder)) {
                    $templateList = array_diff(scandir($templateFolder), array(
                        '..',
                        '.'
                    ));
                    foreach ($templateList as $k => $v) {
                        $fileInfo = new SplFileInfo($templateFolder . $v);
                        if ($fileInfo->getExtension() === 'html' && $fileInfo->getFilename() !== 'index.html') {
                            if (file_exists($templateFolder . $v)) {
                                unlink($templateFolder . $v);
                            }
                        }
                    }
                }
            }
        }

        //  ---  DELETE OLD FILES ---------------
        if (count($configurator['oldFiles']) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator['oldFiles']) as $i) {
                $tempFile = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator['oldFiles'][$i]);
                if (is_file($tempFile)) {
                    unlink($tempFile);
                }
            }
        }

        //  ---  DELETE OLD FOLDERS ---------------
        xoops_load('XoopsFile');
        if (count($configurator['oldFolders']) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator['oldFolders']) as $i) {
                $tempFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator['oldFolders'][$i]);
                /** @var XoopsObjectHandler $folderHandler */
                $folderHandler = XoopsFile::getHandler('folder', $tempFolder);
                $folderHandler->delete($tempFolder);
            }
        }

        //  ---  CREATE FOLDERS ---------------
        if (count($configurator['uploadFolders']) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator['uploadFolders']) as $i) {
                $classUtilities::createFolder($configurator['uploadFolders'][$i]);
            }
        }

        //  ---  COPY blank.png FILES ---------------
        if (count($configurator['copyFiles']) > 0) {
            $file = __DIR__ . '/../assets/images/blank.png';
            foreach (array_keys($configurator['copyFiles']) as $i) {
                $dest = $configurator['copyFiles'][$i] . '/blank.png';
                $classUtilities::copyFile($file, $dest);
            }
        }

        //delete .html entries from the tpl table
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('tplfile') . " WHERE `tpl_module` = '" . $xoopsModule->getVar('dirname', 'n')
               . "' AND `tpl_file` LIKE '%.html%'";
        $xoopsDB->queryF($sql);

    }
    return true;
}
