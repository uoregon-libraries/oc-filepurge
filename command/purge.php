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
    $homePath = $this->user->getHome() . "/files";
    $dataview = new \OC\Files\View("/");
    $userDirectories = $dataview->getDirectoryContent("/", "httpd/unix-directory");

    $this->output->writeln(print_r($dataview->file_exists($homePath . "/foo.txt"), true));
    $this->output->writeln("Beginning scan for <info>{$this->user->getDisplayName()}</info>: $homePath...");
    //$this->output->writeln(print_r($userDirectories, true));
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->output = $output;
    $userid = $input->getArgument("username");
    $this->daysOld = $input->getArgument("days_old");

    if (!$this->userManager->userExists($userid)) {
      $output->writeln("<error>Unknown user $this->userid</error>");
      return;
    }

    $this->user = $this->userManager->get($userid);
    $this->scanFiles();
  }
}
