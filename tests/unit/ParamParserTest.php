<?php

declare(strict_types=1);

namespace Tests\Unit;

use Maduser\Argon\Database\Exception\ParamParserException;
use Maduser\Argon\Database\ParamParser;
use Maduser\Argon\Database\ParamStyle;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

final class ParamParserTest extends TestCase
{
    public function testExtractsNamedParams(): void
    {
        $parser = new ParamParser();
        $sql = 'SELECT * FROM users WHERE id = :id AND email = :email';

        $keys = $parser->extractKeys($sql);

        $this->assertSame(['id', 'email'], $keys);
    }

    public function testReturnsNamedParamsUnmodified(): void
    {
        $parser = new ParamParser();
        $sql = 'SELECT * FROM users WHERE id = :id';
        $params = ['id' => 42];

        $parsed = $parser->parse($sql, $params, ParamStyle::NAMED);

        $this->assertSame($sql, $parsed['sql']);
        $this->assertSame($params, $parsed['params']);
    }

    public function testConvertsToNumberedParams(): void
    {
        $parser = new ParamParser();
        $sql = 'SELECT * FROM users WHERE id = :id AND email = :email';
        $params = ['id' => 1, 'email' => 'foo@example.com'];

        $parsed = $parser->parse($sql, $params);

        $this->assertSame('SELECT * FROM users WHERE id = $1 AND email = $2', $parsed['sql']);
        $this->assertSame([1, 'foo@example.com'], $parsed['params']);
    }

    public function testThrowsOnMissingParams(): void
    {
        $this->expectException(ParamParserException::class);
        $parser = new ParamParser();
        $sql = 'SELECT * FROM users WHERE id = :id';
        $params = [];

        $parser->parse($sql, $params);
    }

    public function testThrowsOnInvalidSqlReplacement(): void
    {
        $parser = new ParamParser();

        $sql = 'SELECT * FROM users WHERE id = :id';
        $params = ['id' => 1];

        $this->expectException(ParamParserException::class);
        $this->expectExceptionMessage('SQL parameter parsing failed for query: :id');

        $parser->parse($sql, $params, ParamStyle::NUMBERED, function (array $match) {
            return 123; // NOT a string, genius
        });
    }
}
