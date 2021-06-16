[![build](https://github.com/bl1ndy/php-project-lvl2/workflows/Project%20CI/badge.svg)](https://github.com/bl1ndy/php-project-lvl2/actions?query=workflow%3AProject%20CI) [![Maintainability](https://api.codeclimate.com/v1/badges/907a6b583b67985d4369/maintainability)](https://codeclimate.com/github/bl1ndy/php-project-lvl2/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/907a6b583b67985d4369/test_coverage)](https://codeclimate.com/github/bl1ndy/php-project-lvl2/test_coverage)

# Description
This project is a CLI-utility for comparing two files and generating diff for them.

It supports only **.json** and **.yml/.yaml** files.

Diff can be presented in 3 formats:
- Stylish
- Plain
- Json
# Installation
```
git clone git@github.com:bl1ndy/gendiff-php.git
```
```
make install
```
# How to use
You can get help information via command:
```
bin/gendiff -h
```
For comparing files, use command:
```
bin/gendiff /path/to/file1.json /path/to/file2.yml
```
You can choose output format by adding `--format` option (default format: `stylish`):
```
bin/gendiff --format plain /path/to/file1.json /path/to/file2.yml
```
Utility supports 3 output formats: `stylish`, `plain` and `json`.
# Examples
### Installatation and presentation of stylish format output
[![asciicast](https://asciinema.org/a/Ovl0BJMfYNrMUk8WHvZ3stUKk.svg)](https://asciinema.org/a/Ovl0BJMfYNrMUk8WHvZ3stUKk)

### Plain format output
[![asciicast](https://asciinema.org/a/sI2h1xuNift31Lg241mQXF7wW.svg)](https://asciinema.org/a/sI2h1xuNift31Lg241mQXF7wW)

### Json format output
[![asciicast](https://asciinema.org/a/U4a4TDjY6vLvUuPHaOlkN4upC.svg)](https://asciinema.org/a/U4a4TDjY6vLvUuPHaOlkN4upC)
