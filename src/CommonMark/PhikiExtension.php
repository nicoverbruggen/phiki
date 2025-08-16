<?php

namespace Phiki\CommonMark;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use Phiki\Phiki;
use Phiki\Theme\Theme;
use League\Config\ConfigurationBuilderInterface;
use Nette\Schema\Expect;

class PhikiExtension implements ConfigurableExtensionInterface
{
    /**
     * @param  bool  $withGutter  Include a gutter in the generated HTML. The gutter typically contains line numbers and helps provide context for the code.
     * @param  bool  $withWrapper  Wrap the generated HTML in an additional `<div>` so that it can be styled with CSS. Useful for avoiding overflow issues.
     */
    public function __construct(
        private string|array|Theme $theme = Theme::Nord,
        private Phiki $phiki = new Phiki,
        private bool $withGutter = false,
        private bool $withWrapper = false,
    ) {}

    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema('phiki', Expect::structure([
            'theme' => Expect::mixed()->default($this->theme),
            'with_gutter' => Expect::bool()->default($this->withGutter),
            'with_wrapper' => Expect::bool()->default($this->withWrapper),
        ]));
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $config = $environment->getConfiguration();

        $theme = $config->get('phiki/theme');
        $withGutter = $config->get('phiki/with_gutter');
        $withWrapper = $config->get('phiki/with_wrapper');

        $environment->addRenderer(
            FencedCode::class,
            new CodeBlockRenderer($theme, $this->phiki, $withGutter, $withWrapper),
            10,
        );
    }
}
