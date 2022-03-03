<?php

namespace Acquia\Blt\Plugin\Commands;

use Acquia\Blt\Robo\BltTasks;
use Acquia\Blt\Robo\Exceptions\BltException;

/**
 * BLT commands class.
 */
class FrontendMultipleCommands extends BltTasks {

  /**
   * Runs all frontend targets.
   *
   * @command source:frontend
   * @aliases sfe
   */
  public function frontend() {
    $this->invokeCommands([
      'source:frontend:build',
      'source:frontend:compile',
    ]);
  }

  /**
   * Performs setup tasks on designated sites/themes.
   *
   * @throws \Acquia\Blt\Robo\Exceptions\BltException
   * @throws \Robo\Exception\TaskException
   *
   * @command source:frontend:build
   * @aliases  sfe:build
   */
  public function build() {

    $theme_dirs = $this->getThemeDir();

    foreach ($theme_dirs as $key => $dir) {
      $task = $this->taskExecStack()
        ->stopOnFail()
        ->dir($dir)
        ->exec('curl -sL https://deb.nodesource.com/setup_14.x | bash -')
        ->exec('apt install -y nodejs')
        ->exec('npm install')
        ->exec('npm install --global gulp-cli')
        ->exec('npm install gulp@^4.0.2 --save')
        ->exec('npm install node-sass');
      $result = $task->run();
      $exit_code = $result->getExitCode();

      if ($exit_code) {
        throw new BltException("Frontend build failed for $dir.");
      }
    }
  }

  /**
   * Compiles all assets.
   *
   * @throws \Acquia\Blt\Robo\Exceptions\BltException
   * @throws \Robo\Exception\TaskException
   *
   * @command source:frontend:compile
   * @aliases sfe:compile
   */
  public function compile(array $options) {

    $theme_dirs = $this->getThemeDir();

    foreach ($theme_dirs as $ndir) {
      $gfilename = $ndir.'/gulpfile.js';
      $nfilename = $ndir.'/package.json';
      if (file_exists($gfilename)) {
        $dir = $ndir;
        $task = $this->taskExecStack()
          ->stopOnFail()
          ->dir($dir)
          ->exec('gulp compile');
      } elseif (file_exists($nfilename) && !file_exists($gfilename) ) {
        $dir = $ndir;
        $task = $this->taskExecStack()
          ->stopOnFail()
          ->dir($dir)
          ->exec('node-sass scss/*.scss css/*.css');
      }

      $result = $task->run();
      $exit_code = $result->getExitCode();

      if ($exit_code) {
        throw new BltException('Frontend compilation failed.');
      }
    }

  }

  /**
   * Resets any specified themes by deleting and reinstalling npm module
   *
   * @throws \Acquia\Blt\Robo\Exceptions\BltException
   * @throws \Robo\Exception\TaskException
   *
   * @command source:frontend:reset
   * @aliases sfe:reset
   */
  public function reset(array $options) {

    $theme_dirs = $this->getThemeDir();

    foreach ($theme_dirs as $dir) {
      $task = $this->taskExecStack()
        ->stopOnFail()
        ->dir($dir)
        ->exec('rm -r node_modules')
        ->exec('npm install')
        ->exec('gulp compile');

      $result = $task->run();
      $exit_code = $result->getExitCode();

      if ($exit_code) {
        throw new BltException("Frontend reset failed for $dir.");
      }
    }

  }

  /**
   * Returns the absolute path to the theme directory.
   *
   * @return array
   *   The absolute paths of all theme directories.
   */
  private function getThemeDir() {

  $dirs = array_filter(glob($this->getConfigValue('docroot') . '/themes/custom/*'), 'is_dir');

    $site_theme_dirs = [];

    foreach ($dirs as $key => $dir) {
      $filename = $dir.'/gulpfile.js';
      if (file_exists($filename)) {
        $theme_dir = $dir;
      }
      $site_theme_dirs[] = $theme_dir;
    }
    return $site_theme_dirs;
  }
}
