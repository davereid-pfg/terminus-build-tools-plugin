<?php
/**
 * Terminus Plugin that contain a collection of commands useful during
 * the build step on a [Pantheon](https://www.pantheon.io) site that uses
 * a GitHub PR workflow.
 *
 * See README.md for usage information.
 */

namespace Pantheon\TerminusBuildTools\Commands;

use Consolidation\OutputFormatters\StructuredData\PropertyList;
use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Exceptions\TerminusException;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\ProcessUtils;
use Consolidation\AnnotatedCommand\AnnotationData;
use Consolidation\AnnotatedCommand\CommandData;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\Semver\Comparator;

/**
 * Secret Delete Command
 */
class SecretDeleteCommand extends BuildToolsBase
{

    /**
     * Show a secret set via build:secrets:set
     *
     * @command build:secrets:show
     * @alias build:secrets:get
     *
     * @param string $site_env_id Name of the environment to set the secret in.
     * @param string $key Item to set (or empty to delete everything)
     * @option file Name of file to store secrets in
     */
    public function show(
        $site_env_id,
        $key,
        $options = [
            'file' => 'secrets.json',
        ])
    {
        $secretValues = $this->downloadSecrets($site_env_id, $options['file']);
        if (!array_key_exists($key, $secretValues))
        {
            throw new TerminusException('Key {key} not found in {file}', ['key' => $key, 'file' => $options['file']]);
        }
        return $secretValues[$key];
    }
}