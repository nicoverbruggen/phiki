<?php

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use Phiki\CommonMark\PhikiExtension;
use Phiki\Theme\Theme;

it('registers renderers', function () {
    $environment = new Environment;

    $environment
        ->addExtension(new CommonMarkCoreExtension)
        ->addExtension(new PhikiExtension('github-dark'));

    $markdown = new MarkdownConverter($environment);
    $generated = $markdown->convert(<<<'MD'
    ```php
    class A {}
    ```
    MD)->getContent();

    expect($generated)
        ->toContain('phiki')
        ->toContain('github-dark')
        ->toContain('<span class="token" style="color: #b392f0;">A</span>');
});

it('auto detects grammar if not provided', function () {
    $environment = new Environment;

    $environment
        ->addExtension(new CommonMarkCoreExtension)
        ->addExtension(new PhikiExtension('github-dark'));

    $markdown = new MarkdownConverter($environment);
    $generated = $markdown->convert(<<<'MD'
    ```
    <?php

    echo "Hello, world!";
    ```
    MD)->getContent();

    expect($generated)
        ->toContain('data-language="php"');
});

it('can be configured using environment config array', function () {
    $environment = new Environment([
        'phiki' => [
            'theme' => Theme::GithubLight,
            'with_gutter' => false,
            'with_wrapper' => false,
        ],
    ]);

    $environment
        ->addExtension(new CommonMarkCoreExtension)
        ->addExtension(new PhikiExtension);
    $markdown = new MarkdownConverter($environment);

    $generated = $markdown->convert(<<<'MD'
    ```php
    class A {}
    ```
    MD)->getContent();

    expect($generated)->toMatchSnapshot();
});
