<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiffStylishFormat(): void
    {
        $expected = <<<DOC
        {
            common: {
              + follow: false
                setting1: Value 1
              - setting2: 200
              - setting3: true
              + setting3: null
              + setting4: blah blah
              + setting5: {
                    key5: value5
                }
                setting6: {
                    doge: {
                      - wow: 
                      + wow: so much
                    }
                    key: value
                  + ops: vops
                }
            }
            group1: {
              - baz: bas
              + baz: bars
                foo: bar
              - nest: {
                    key: value
                }
              + nest: str
            }
          - group2: {
                abc: 12345
                deep: {
                    id: 45
                }
            }
          + group3: {
                deep: {
                    id: {
                        number: 45
                    }
                }
                fee: 100500
            }
        }
        DOC;

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
        $expected = <<<DOC
        Property 'common.follow' was added with value: false
        Property 'common.setting2' was removed
        Property 'common.setting3' was updated. From true to null
        Property 'common.setting4' was added with value: 'blah blah'
        Property 'common.setting5' was added with value: [complex value]
        Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
        Property 'common.setting6.ops' was added with value: 'vops'
        Property 'group1.baz' was updated. From 'bas' to 'bars'
        Property 'group1.nest' was updated. From [complex value] to 'str'
        Property 'group2' was removed
        Property 'group3' was added with value: [complex value]
        DOC;

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
        $expected = <<<DOC
        [
            {
                "key": "common",
                "type": "complex",
                "oldValue": null,
                "newValue": null,
                "children": [
                    {
                        "key": "follow",
                        "type": "added",
                        "oldValue": null,
                        "newValue": false,
                        "children": null
                    },
                    {
                        "key": "setting1",
                        "type": "unchanged",
                        "oldValue": "Value 1",
                        "newValue": "Value 1",
                        "children": null
                    },
                    {
                        "key": "setting2",
                        "type": "removed",
                        "oldValue": 200,
                        "newValue": null,
                        "children": null
                    },
                    {
                        "key": "setting3",
                        "type": "updated",
                        "oldValue": true,
                        "newValue": null,
                        "children": null
                    },
                    {
                        "key": "setting4",
                        "type": "added",
                        "oldValue": null,
                        "newValue": "blah blah",
                        "children": null
                    },
                    {
                        "key": "setting5",
                        "type": "added",
                        "oldValue": null,
                        "newValue": {
                            "key5": "value5"
                        },
                        "children": null
                    },
                    {
                        "key": "setting6",
                        "type": "complex",
                        "oldValue": null,
                        "newValue": null,
                        "children": [
                            {
                                "key": "doge",
                                "type": "complex",
                                "oldValue": null,
                                "newValue": null,
                                "children": [
                                    {
                                        "key": "wow",
                                        "type": "updated",
                                        "oldValue": "",
                                        "newValue": "so much",
                                        "children": null
                                    }
                                ]
                            },
                            {
                                "key": "key",
                                "type": "unchanged",
                                "oldValue": "value",
                                "newValue": "value",
                                "children": null
                            },
                            {
                                "key": "ops",
                                "type": "added",
                                "oldValue": null,
                                "newValue": "vops",
                                "children": null
                            }
                        ]
                    }
                ]
            },
            {
                "key": "group1",
                "type": "complex",
                "oldValue": null,
                "newValue": null,
                "children": [
                    {
                        "key": "baz",
                        "type": "updated",
                        "oldValue": "bas",
                        "newValue": "bars",
                        "children": null
                    },
                    {
                        "key": "foo",
                        "type": "unchanged",
                        "oldValue": "bar",
                        "newValue": "bar",
                        "children": null
                    },
                    {
                        "key": "nest",
                        "type": "updated",
                        "oldValue": {
                            "key": "value"
                        },
                        "newValue": "str",
                        "children": null
                    }
                ]
            },
            {
                "key": "group2",
                "type": "removed",
                "oldValue": {
                    "abc": 12345,
                    "deep": {
                        "id": 45
                    }
                },
                "newValue": null,
                "children": null
            },
            {
                "key": "group3",
                "type": "added",
                "oldValue": null,
                "newValue": {
                    "deep": {
                        "id": {
                            "number": 45
                        }
                    },
                    "fee": 100500
                },
                "children": null
            }
        ]
        DOC;

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
