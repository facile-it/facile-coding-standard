<?php

/**
 * This PHP script is useful to dump rules descriptions into Markdown,
 * so that it's easier to open PRs with descriptive content aimed at
 * adding new rules to this repository ruleset.
 *
 * Launching this script with no options will dump all rules which are
 * not already enabled.
 *
 * TODO: Launching it with arguments will dump the listed rules.
 */

use Facile\CodingStandardsTest\RulesMaintenance\Dumper;

require __DIR__ . '/vendor/autoload.php';

$output = __DIR__ . '/dump_rules.md';
@unlink($output);

$dumper = new Dumper();

foreach ($dumper->getUnlistedRulesDescription(true) as $ruleName => $ruleDescription) {
    if (str_starts_with($ruleName, 'warning')) {
        echo $ruleDescription;
    } else {
        echo 'dumping rule ' . $ruleName . '...' . \PHP_EOL;
        file_put_contents($output, $ruleDescription, \FILE_APPEND);
    }
}

echo 'Done';
