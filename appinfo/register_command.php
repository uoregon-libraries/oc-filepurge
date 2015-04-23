<?php
// The File Purge is in the public domain under a CC0 license.

$application->add(new OCA\FilePurge\Command\Purge(OC_User::getManager()));
