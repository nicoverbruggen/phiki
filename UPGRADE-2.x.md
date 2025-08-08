# Upgrading from 1.x to 2.0

# Changelog

This section contains all of the necessary steps to upgrade Phiki from `v1.x` to `v2.0` in your project.

## High impact

### Minimum PHP version increased to PHP 8.4

PHP 8.4 uses a newer version of the PCRE2 engine which adds basic support for variable-length lookbehind assertions. This is something that was mentioned in the `README` of Phiki 1.x as a huge limitation when it comes to supporting more complicated TextMate grammars since other engines like Oniguruma and ECMAScript support "true" variable-length lookbehinds.

By bumping Phiki's minimum PHP version to PHP 8.4 we can truly take advantage of this functionality and improve compatibility across the board.

### Grammar changes

In order to improve Phiki's reliabilty when it comes to processing input text and outputting accurate HTML, we've made the decision to strip back the grammars that Phiki ships with.

The `Phiki\Grammar\Grammar` enum now contains a small list of supported grammars, all of which have been tested thoroughly and compared to `vscode-textmate` for equality.

We apologise for any inconvenience this causes in your own projects. Pull request are (very) welcomed to add support for new grammars, but we'll also do this over time.

## Medium impact

### Removed support for terminal highlighting

We've removed support for generating ANSI-backed highlighted code in the terminal. This means you'll no longer be able to call `Phiki::codeToTerminal()`.

We've also removed the `phiki` binary that shipped with 1.x as it also wasn't used very often and added unnecessary maintenance burden to the project.

## Low impact

### `DefaultGrammars` and `DefaultThemes` removed

The `Phiki\Grammar\DefaultGrammars` and `Phiki\Theme\DefaultThemes` classes have been removed and the management of these have been simplified to reduce the maintenance burden.

Phiki is already a large and complex codebase so the slimmer we can make it, the better.

These classes were for internal use only so hopefully they won't affect your userland code.
