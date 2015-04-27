<?php

namespace OCA\FilePurge\Command;

use OC\ForbiddenException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Purge extends Command {

  /**
   * @var \OC\User\Manager $userManager
   */
  private $userManager;

  public function __construct(\OC\User\Manager $userManager) {
    $this->userManager = $userManager;
    parent::__construct();
  }

  protected function configure() {
    $this
      ->setName("files:purge")
      ->setDescription("purges old files")
      ->addArgument(
        "username",
        InputArgument::REQUIRED,
        "Which user's files will be purged?"
      )
      ->addArgument(
        "days_old",
        InputArgument::REQUIRED,
        "How old (in days) must a file be before purging it?"
      );
  }

  protected function scanFiles() {
    // This is terribly hard-coded - there must be a "right way" to get at this
    // directory, but I'm done wrestling with it
    $purgePath = "/" . $this->user->getUID() . "/files/auto-purge";
    $this->output->writeln("Beginning scan for files in <info>$purgePath</info> older than <info>{$this->secondsOld}</info> seconds...");

    $dataview = new \OC\Files\View($purgePath);
    $manager = \OC\Files\Filesystem::getMountManager();
    $root = new \OC\Files\Node\Root($manager, $dataview, $this->user);
    $this->purgeAll($root);
  }

  protected function purgeAll(\OC\Files\Node\Root $root) {
    foreach ($root->getDirectoryListing() as $file) {
      $fname = $file->getName();
      $modSeconds = time() - $file->getMTime();
      $this->output->write("$fname: Last modified $modSeconds seconds ago: ");
      if ($modSeconds > $this->secondsOld) {
        $this->output->writeln("\033[31mDeleting file\033[0m");
        $file->delete();
      }
      else {
        $this->output->writeln("Skipping file");
      }
    }
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->output = $output;
    $userid = $input->getArgument("username");
    $this->secondsOld = $input->getArgument("days_old") * 86400;

    if (!$this->userManager->userExists($userid)) {
      $output->writeln("<error>Unknown user $this->userid</error>");
      return;
    }

    $this->user = $this->userManager->get($userid);
    $this->scanFiles();
  }
}
