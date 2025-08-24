import { pascalCase } from "change-case";
import { grammars } from "tm-grammars";
import { themes } from "tm-themes";
import fs from "node:fs";
import { basePath } from "./utils.js";
import tablemark from 'tablemark'

console.log("Updating grammars...");

const cases = {};
const aliases = {};
const scopeNames = {};
const exclusions = ["source.bicep", "source.po", "source.wenyan"];

grammars.forEach((grammar) => {
    if (grammar.injectTo) {
        console.warn(
            `Skipping grammar ${grammar.name} as it is an injection grammar.`
        );
        return;
    }

    if (exclusions.includes(grammar.scopeName)) {
        console.warn(
            `Skipping grammar ${grammar.name} as it is in the exclusions list.`
        );
        return;
    }

    cases[pascalCase(grammar.name)] = grammar.name;
    aliases[pascalCase(grammar.name)] = grammar.aliases ?? [];
    scopeNames[pascalCase(grammar.name)] = grammar.scopeName;
});

console.log(`Found ${Object.keys(cases).length} grammars.`);

const grammarsTable = Object.keys(cases)
    .map(key => ({
        name: `\`${cases[key]}\``,
        aliases: aliases[key].map(a => `\`${a}\``).join(", ") || "—",
        scopeName: `\`${scopeNames[key]}\``,
        enum: `\`Grammar::${key}\``,
    }))

const grammarsTableMarkdown = tablemark(grammarsTable, {
    columns: ["Name", "Aliases", "Scope name", "Enum case"],
})

console.log("Writing grammars stub...");

fs.writeFileSync(basePath("docs/snippets/AvailableGrammarsList.mdx"), grammarsTableMarkdown);

console.log("Grammars updated successfully.");

const themeCases = {};

themes.forEach((theme) => {
    themeCases[pascalCase(theme.name)] = theme.name;
});

console.log(`Found ${Object.keys(themeCases).length} themes.`);

const themesTable = Object.keys(themeCases)
    .map((key) => ({
        name: `\`${themeCases[key]}\``,
        enum: `\`Theme::${key}\``,
    }));

const themesTableMarkdown = tablemark(themesTable, {
    columns: ["Name", "Enum case"],
});

console.log("Writing themes stub...");

fs.writeFileSync(basePath("docs/snippets/AvailableThemesList.mdx"), themesTableMarkdown);

console.log("Themes updated successfully.");
