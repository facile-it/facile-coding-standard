<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

class SingleLineVarPhpdocFixer extends AbstractFixer
{
    private const NAME = 'SingleLineVarPhpdocFixer/single_line_var_phpdoc';

    public function getName()
    {
        return self::NAME;
    }

    public function getDefinition()
    {
        return new FixerDefinition('Phpdoc comments containing only @var must be on a single line', []);
    }

    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound(T_DOC_COMMENT);
    }

    protected function applyFix(SplFileInfo $file, Tokens $tokens):void
    {
        /** @var Token $token */
        foreach ($tokens as $index => $token) {
            if (! $token->isComment()) {
                continue;
            }

            $comment = preg_replace_callback(
                '/(\s*)\/\*[\s*]+@var (.*)[\s*]+\*\//',
                function($matches) {
                    return $matches[1].'/** @var '.trim($matches[2]).' */';
                },
                $token->getContent()
            );

            if ($comment) {
                $token->setContent($comment);
            }
        }
    }

}
