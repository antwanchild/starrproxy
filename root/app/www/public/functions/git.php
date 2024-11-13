<?php

/*
----------------------------------
 ------  Created: 111224   ------
 ------  Austin Best	   ------
----------------------------------
*/

function gitBranch()
{
    if (!defined('DOCKERFILE_BRANCH')) {
        return 'Source';
    }
       
    return DOCKERFILE_BRANCH;
}

function gitHash()
{
    if (!defined('DOCKERFILE_COMMIT')) {
        return 'Unknown';
    }

    return DOCKERFILE_COMMIT;
}

function gitMessage()
{
    if (!defined('DOCKERFILE_COMMIT_MSG')) {
        return 'Unknown';
    }

    return DOCKERFILE_COMMIT_MSG;
}

function gitVersion()
{
    if (!defined('DOCKERFILE_COMMITS')) {
        return '<span class="text-small text-secondary" title="Branch: ' . gitBranch() . ' - Commit: ' . gitHash() . '">v0.0.0</span>';
    }

    return '<span class="text-small text-secondary" title="Branch: ' . gitBranch() . ' - Commit: ' . gitHash() . '">v' . APP_X . '.' . APP_Y . '.' . DOCKERFILE_COMMITS . '</span>';
}
