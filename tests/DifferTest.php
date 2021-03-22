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
                "type": "complex",
                "key": "common",
                "values": [],
                "children": [
                    {
                        "type": "added",
                        "key": "follow",
                        "values": {
                            "new": false
                        },
                        "children": []
                    },
                    {
                        "type": "unchanged",
                        "key": "setting1",
                        "values": {
                            "old": "Value 1",
                            "new": "Value 1"
                        },
                        "children": []
                    },
                    {
                        "type": "removed",
                        "key": "setting2",
                        "values": {
                            "old": 200
                        },
                        "children": []
                    },
                    {
                        "type": "changed",
                        "key": "setting3",
                        "values": {
                            "old": true,
                            "new": null
                        },
                        "children": []
                    },
                    {
                        "type": "added",
                        "key": "setting4",
                        "values": {
                            "new": "blah blah"
                        },
                        "children": []
                    },
                    {
                        "type": "added",
                        "key": "setting5",
                        "values": {
                            "new": {
                                "key5": "value5"
                            }
                        },
                        "children": []
                    },
                    {
                        "type": "complex",
                        "key": "setting6",
                        "values": [],
                        "children": [
                            {
                                "type": "complex",
                                "key": "doge",
                                "values": [],
                                "children": [
                                    {
                                        "type": "changed",
                                        "key": "wow",
                                        "values": {
                                            "old": "",
                                            "new": "so much"
                                        },
                                        "children": []
                                    }
                                ]
                            },
                            {
                                "type": "unchanged",
                                "key": "key",
                                "values": {
                                    "old": "value",
                                    "new": "value"
                                },
                                "children": []
                            },
                            {
                                "type": "added",
                                "key": "ops",
                                "values": {
                                    "new": "vops"
                                },
                                "children": []
                            }
                        ]
                    }
                ]
            },
            {
                "type": "complex",
                "key": "group1",
                "values": [],
                "children": [
                    {
                        "type": "changed",
                        "key": "baz",
                        "values": {
                            "old": "bas",
                            "new": "bars"
                        },
                        "children": []
                    },
                    {
                        "type": "unchanged",
                        "key": "foo",
                        "values": {
                            "old": "bar",
                            "new": "bar"
                        },
                        "children": []
                    },
                    {
                        "type": "changed",
                        "key": "nest",
                        "values": {
                            "old": {
                                "key": "value"
                            },
                            "new": "str"
                        },
                        "children": []
                    }
                ]
            },
            {
                "type": "removed",
                "key": "group2",
                "values": {
                    "old": {
                        "abc": 12345,
                        "deep": {
                            "id": 45
                        }
                    }
                },
                "children": []
            },
            {
                "type": "added",
                "key": "group3",
                "values": {
                    "new": {
                        "deep": {
                            "id": {
                                "number": 45
                            }
                        },
                        "fee": 100500
                    }
                },
                "children": []
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
