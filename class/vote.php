<?php
/**
 * ****************************************************************************
 *  - TDMPicture By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - Licence PRO Copyright (c)  (http://www.tdmxoops.net)
 *
 * Cette licence, contient des limitations!!!
 *
 * 1. Vous devez posséder une permission d'exécuter le logiciel, pour n'importe quel usage.
 * 2. Vous ne devez pas l' étudier,
 * 3. Vous ne devez pas le redistribuer ni en faire des copies,
 * 4. Vous n'avez pas la liberté de l'améliorer et de rendre publiques les modifications
 *
 * @license     TDMFR PRO license
 * @author      TDMFR ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */
// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

class TdmPictureVote extends XoopsObject
{
    // constructor
    /**
     * TdmPictureVote constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('vote_id', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('vote_file', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('vote_album', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('vote_artiste', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('vote_ip', XOBJ_DTYPE_TXTBOX, null, false);
    }
}

/**
 * Class TdmPictureVoteHandler
 */
class TdmPictureVoteHandler extends XoopsPersistableObjectHandler
{
    /**
     * TdmPictureVoteHandler constructor.
     * @param XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, 'tdmpicture_vote', 'TdmPictureVote', 'vote_id', 'vote_ip');
    }
}
