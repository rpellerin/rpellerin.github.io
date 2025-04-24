Title: Kotlin
Date: 2024-07-26 15:10
Category: Code
Tags: kotlin, java, code
Slug: kotlin
Authors: Romain Pellerin
Summary: Cheatsheet for beginners - such as myself!

# Setup

## Install Kotlin

Installing Kotlin is not necessary. Only Java is. IntelliJ Ultimate Edition (paid) or Community Edition (free) both come with the Kotlin plugin bundled and enabled by default.

## Install Java (required)

    :::bash
    sudo apt update
    sudo apt install openjdk-21-jdk # or newer, check with `apt search openjdk`

## Install Apache Maven

[Installing Maven, a popular build tool, is not required, as IntelliJ comes with a bundled version of Maven.](https://stackoverflow.com/questions/66399278/having-maven-plugins-in-intellij-idea-without-maven-installation-in-computer) However, installing your own version might come in handy, to be able to run Maven from the command line outside IntelliJ, or to run a specific version of Maven. **It does not hurt to install Maven globally on the system, it won't conflict with IntelliJ's Maven, but it is not recommended.**

    :::bash
    sudo apt update
    sudo apt install maven

### Maven Wrapper

A better alternative to installing Maven system-wide is to [use the Maven Wrapper in a project.](https://maven.apache.org/wrapper/) It is usually a file named `mvnw`. **Its primary purpose is to ensure that anyone building the project uses the exact same version of Apache Maven, without needing to install Maven manually beforehand.**

It comes with `.mvn/wrapper/maven-wrapper.properties`, a configuration file specifying which version of Maven should be used and where to download it from (usually Maven Central).

Instead of running `mvn <goal>` (which uses the globally installed Maven, if any), users run `./mvnw <goal>`.

# IntelliJ IDEA Ultimate

Although technically any IDE with the right extensions will do, it's recommended to use IntelliJ IDEA Ultimate. There is also the Community Edition that is completely free to use.

Kotlin is included in all IntelliJ IDEA versions and releases.

## Install

1. [Download Jetbrains' Toolbox App](https://www.jetbrains.com/toolbox-app/)
2. Extract `jetbrains-toolbox` from the archive and move it under `~/.local/bin`
3. Launch it. From there you can install IntelliJ Ultimate.

## Setup IntelliJ

- `Settings > Build, Execution, Deployment > Build Tools > Maven > Importing > Automatically download`. Check the 3 checkboxes "Sources", "Documentation" and "Annotations".
- `Settings > Editor > General > Auto Import`, check `Add unambiguous imports on the fly` and `Optimize imports on the fly`.
- `Settings > Editor > Tools > Actions on Save`, check `Reformat code`, `Optimize imports`, `Rearrange code` and `Run code cleanup`.

## Plugins

`File` > `Settings` > `Plugins`:

- `SonarQube for IDE` for code quality checks
- `GitHub Copilot`
- `[GitToolBox](https://plugins.jetbrains.com/plugin/7499-gittoolbox)` for git blame on lines

## Common shortcuts (as of April 2025)

1.  **`Alt+Enter`**: Show Intention Actions & Quick Fixes. This is arguably the most crucial shortcut. It offers context-aware suggestions, from fixing errors and warnings to importing classes, generating code, and applying quick refactorings.
1.  **`Double Shift`**: Search Everywhere. Quickly find files, classes, symbols, actions, settings, or even Git commits anywhere in your project or the IDE itself.
1.  **`Alt+J`**: Equivalent of `Ctrl+D` in VS Code ("Add Selection for Next Occurrence").
1.  **`Ctrl+Shift+A`**: Find Action. If you know the name of an action (like "Reformat Code" or "Toggle Case") but not the shortcut, use this to find and execute it. It also shows the shortcut if one exists.
1.  **`Ctrl+B` or `Ctrl+Click`**: Go to Declaration or Usages. Navigate to the source declaration of a variable, method, or class. If used on the declaration itself, it finds usages.
1.  **`Alt+F7`**: Find Usages. Shows all places where the symbol under the caret is used throughout the project in the Find tool window.
1.  **`Ctrl+Alt+L`**: Reformat Code. Automatically formats the current file or selected code block according to your project's code style settings.
1.  **`Ctrl+/`**: Comment/Uncomment with Line Comment. Quickly comment or uncomment the current line or selected block using single-line comments (`//`).
1.  **`Ctrl+Shift+/`**: Comment/Uncomment with Block Comment. Comment or uncomment the selected block using block comments (`/* ... */`).
1.  **`Ctrl+W` / `Ctrl+Shift+W`**: Extend / Shrink Selection. Intelligently selects increasingly larger logical blocks of code (or shrinks the selection). Very useful for selecting statements, methods, or classes without using the mouse.
1.  **`Ctrl+D`**: Duplicate Line or Selection. Copies the current line (or selected block) and pastes it immediately below.
1.  **`Ctrl+Y`**: Delete Line. Deletes the entire line where the caret is positioned.
1.  **`Shift+F6`**: Rename Refactoring. Safely rename variables, methods, classes, files, etc., updating all usages across the project.
1.  **`Ctrl+Alt+M`**: Extract Method Refactoring. Turns a selected block of code into a new method.
1.  **`Ctrl+Alt+V`**: Extract Variable Refactoring. Creates a new variable from a selected expression.
1.  **`Ctrl+E`**: Recent Files. Shows a popup list of recently opened files for quick navigation. Press `Ctrl+E` again to see only recently _changed_ files.
1.  **`Ctrl+N`**: Go to Class. Quickly navigate to any class definition by typing its name (CamelHumps matching works).
1.  **`Ctrl+Shift+N`**: Go to File. Quickly navigate to any file (not just classes) by typing its name.
1.  **`Shift+F10`**: Run. Executes the current run/debug configuration.
1.  **`Shift+F9`**: Debug. Starts debugging the current run/debug configuration.
1.  **`Alt+1`**: Toggle Project Tool Window. Shows or hides the Project view panel, typically used for navigating the file tree. `Esc` usually returns focus to the editor from a tool window.

**Note:** These shortcuts are based on the default Linux keymap in IntelliJ IDEA. You can customize these shortcuts in `Settings` > `Keymap`. Some shortcuts might conflict with global OS shortcuts (especially on certain Linux distributions); IntelliJ usually warns you about this and provides options to resolve conflicts.

### GitHub Copilot shortcuts

1. **`Tab`**: Accept the current inline suggestion. This is the primary way to insert the code Copilot suggests.
1. **`Esc`**: Dismiss the current inline suggestion. Use this if you don't want the suggestion and want to keep typing.
1. **`Alt+]`**: Show the next inline suggestion. Cycles forward through alternative suggestions if Copilot has more than one.
1. **`Alt+[`**: Show the previous inline suggestion. Cycles backward through alternative suggestions.
1. **`Alt+\`**: Trigger inline suggestion manually. If Copilot hasn't automatically shown a suggestion, or you dismissed one, this asks Copilot to provide one based on the current context. _Note: This shortcut might conflict with other IntelliJ or OS shortcuts and may need rebinding._
1. **`Ctrl+Right Arrow`**: Accept the next word of the suggestion. Useful for accepting suggestions incrementally.
1. **`Ctrl+Enter`** (or sometimes **`Alt+Enter`** - check your keymap): Open GitHub Copilot panel/tool window. Shows multiple suggestions (often up to 10) in a separate panel for comparison. _Note: `Alt+Enter` is the default for IntelliJ's "Show Intention Actions", so `Ctrl+Enter` might be the more likely default for this specific Copilot action, but it's best to verify in your Keymap settings under the action typically named `Open GitHub Copilot` or similar._
