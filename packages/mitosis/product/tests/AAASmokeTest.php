<?php



namespace Mitosis\Product\Tests;

class AAASmokeTest extends TestCase
{
    const MIN_PHP_VERSION = '7.1.3';

    /**
     * @test
     */
    public function smoke()
    {
        $this->assertTrue(true);
    }

    /**
     * Test for minimum PHP version
     *
     * @depends smoke
     * @test
     */
    public function php_version_satisfies_requirements()
    {
        $this->assertFalse(version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<'),
            'PHP version ' . self::MIN_PHP_VERSION . ' or greater is required but only '
            . PHP_VERSION . ' found.');
    }
}
