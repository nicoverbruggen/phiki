<?php

namespace Phiki;

use Phiki\Contracts\ExtensionInterface;
use Phiki\Grammar\Grammar;
use Phiki\Grammar\GrammarRepository;
use Phiki\Grammar\ParsedGrammar;
use Phiki\Theme\ParsedTheme;
use Phiki\Theme\Theme;
use Phiki\Theme\ThemeRepository;

class Environment
{
    public readonly GrammarRepository $grammars;

    public readonly ThemeRepository $themes;

    public function __construct()
    {
        $this->grammars = new GrammarRepository;
        $this->themes = new ThemeRepository;
    }

    public function extend(ExtensionInterface $extension): static
    {
        $extension->register($this);

        return $this;
    }

    public function grammar(string $slug, string|ParsedGrammar $grammar): static
    {
        $this->grammars->register($slug, $grammar);

        return $this;
    }

    public function theme(string $slug, string|ParsedTheme $theme): static
    {
        $this->themes->register($slug, $theme);

        return $this;
    }
}
