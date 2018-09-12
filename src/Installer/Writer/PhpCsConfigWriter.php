<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer\Writer;

final class PhpCsConfigWriter implements PhpCsConfigWriterInterface
{
    /**
     * @param string $filename
     */
    public function writeConfigFile(string $filename): void
    {
        file_put_contents($filename, $this->createConfigSource());
    }

    /**
     * @return string
     */
    private function createConfigSource(): string
    {
        $contents = <<<FILE
<?php

/*
 * Additional rules or rules to override.
 * These rules will be added to default rules or will override them if the same key already exists.
 */
 
\$additionalRules = [];
\$rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider([
    new Facile\CodingStandards\Rules\DefaultRulesProvider(),
    new Facile\CodingStandards\Rules\ArrayRulesProvider(\$additionalRules)
]);

\$config = PhpCsFixer\Config::create();
\$config->setRules(\$rulesProvider->getRules());
\$config->setRiskyAllowed(false);

\$finder = PhpCsFixer\Finder::create();

/*
 * You can set manually these paths. 
 */
\$autoloadPathProvider = new Facile\CodingStandards\AutoloadPathProvider();
\$finder->in(\$autoloadPathProvider->getPaths());

\$config->setFinder(\$finder);

return \$config;

FILE;

        return $contents;
    }
}
