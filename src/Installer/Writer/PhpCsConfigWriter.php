<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer\Writer;

use Facile\CodingStandards\Installer\Provider\SourcePaths\ProviderInterface;

final class PhpCsConfigWriter implements PhpCsConfigWriterInterface
{
    /**
     * @var ProviderInterface
     */
    private $sourcePathsProvider;

    /**
     * PhpCsConfigWriter constructor.
     *
     * @param ProviderInterface $sourcePathsProvider
     */
    public function __construct(ProviderInterface $sourcePathsProvider)
    {
        $this->sourcePathsProvider = $sourcePathsProvider;
    }

    /**
     * @param string $filename
     */
    public function writeConfigFile(string $filename)
    {
        file_put_contents($filename, $this->createConfigSource());
    }

    /**
     * @return string
     */
    private function createConfigSource(): string
    {
        $finderPaths = $this->sourcePathsProvider->getSourcePaths();

        $finderPathsString = var_export($finderPaths, true);

        $contents = <<<FILE
<?php

/*
 * Additional rules or rules to override.
 * These rules will be added to default rules or will override them if the same key already exists.
 */
\$additionalRules = [];
\$rules = new Facile\CodingStandards\DefaultRules(\$additionalRules);

\$config = PhpCsFixer\Config::create();
\$config->setRules(\$rules->getRules());

\$config->setUsingCache(false);
\$config->setRiskyAllowed(false);

\$finder = PhpCsFixer\Finder::create();
\$finder->in($finderPathsString);

\$config->setFinder(\$finder);

return \$config;

FILE;

        return $contents;
    }
}
