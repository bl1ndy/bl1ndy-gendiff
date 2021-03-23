<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiffStylishFormat(): void
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/Stylish_result.txt");

        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__ . "/fixtures/before.json",
                __DIR__ . "/fixtures/after.json",
            ),
        );
        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__ . "/fixtures/before.yml",
                __DIR__ . "/fixtures/after.yml",
            ),
        );
    }

    public function testGenDiffPlainFormat(): void
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/Plain_result.txt");

        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__ . "/fixtures/before.json",
                __DIR__ . "/fixtures/after.json",
                'plain'
            ),
        );
        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__ . "/fixtures/before.yml",
                __DIR__ . "/fixtures/after.yml",
                'plain'
            ),
        );
    }

    public function testGenDiffJsonFormat(): void
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/Json_result.txt");

        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__ . "/fixtures/before.json",
                __DIR__ . "/fixtures/after.json",
                'json'
            ),
        );
        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__ . "/fixtures/before.yml",
                __DIR__ . "/fixtures/after.yml",
                'json'
            ),
        );
    }
}
