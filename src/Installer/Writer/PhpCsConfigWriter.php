<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer\Writer;

final class PhpCsConfigWriter implements PhpCsConfigWriterInterface
{
    /**
     * @param null|string $filename
     * @param bool $noDev
     * @param bool $noRisky
     */
    public function writeConfigFile(?string $filename = null, bool $noDev = false, bool $noRisky = false): void
    {
        $filename = $filename ?: '.php-cs-fixer.dist.php';
        \file_put_contents($filename, $this->createConfigSource($noDev, $noRisky));
    }

    /**
     * @param bool $noDev
     * @param bool $noRisky
     *
     * @return string
     */
    private function createConfigSource(bool $noDev = false, bool $noRisky = false): string
    {
        $rulesProviderConfig = $this->createRulesProviderConfig($noRisky);

        $autoloadPathProvider = '$autoloadPathProvider = new Facile\CodingStandards\AutoloadPathProvider();';

        if ($noDev) {
            $autoloadPathProvider = '$autoloadPathProvider = new Facile\CodingStandards\AutoloadPathProvider(null, null, false);';
        }

        return <<<FILE
<?php

/*
 * Additional rules or rules to override.
 * These rules will be added to default rules or will override them if the same key already exists.
 */
 
$rulesProviderConfig

\$config = PhpCsFixer\Config::create();
\$config->setRules(\$rulesProvider->getRules());

\$finder = PhpCsFixer\Finder::create();

/*
 * You can set manually these paths:
 */
$autoloadPathProvider
\$finder->in(\$autoloadPathProvider->getPaths());

\$config->setFinder(\$finder);

return \$config;

FILE;
    }

    private function createRulesProviderConfig(bool $noRisky = false): string
    {
        $providersLine = [
            '    new Facile\CodingStandards\Rules\DefaultRulesProvider(),',
        ];

        if (false === $noRisky) {
            $providersLine[] = '    new Facile\CodingStandards\Rules\RiskyRulesProvider(),';
        }

        $providersLine[] = '    new Facile\CodingStandards\Rules\ArrayRulesProvider($additionalRules),';

        $providersLine = \implode("\n", $providersLine);

        return <<<TEXT
\$additionalRules = [];
\$rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider([
$providersLine
]);
TEXT;
    }
}
