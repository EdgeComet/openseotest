<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\Config;
use Ost\Controllers\PromptController;
use Ost\Response;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the PromptController (QR code prompt pages).
 */
class PromptControllerTest extends TestCase
{
    protected function setUp(): void
    {
        Response::reset();
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function slugProvider(): array
    {
        return [
            'prompt 1' => ['1'],
            'prompt 2' => ['2'],
            'prompt 3' => ['3'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('slugProvider')]
    public function testPromptPageIsNoindex(string $slug): void
    {
        $output = (new PromptController())->show($slug);

        $this->assertStringContainsString('<meta name="robots" content="noindex, nofollow, noarchive">', $output);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('slugProvider')]
    public function testPromptPageContainsConfiguredText(string $slug): void
    {
        $prompt = Config::get('prompts', $slug);
        $output = (new PromptController())->show($slug);

        $this->assertStringContainsString(htmlspecialchars($prompt['text']), $output);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('slugProvider')]
    public function testPromptPageReturns200WithCopyButton(string $slug): void
    {
        $output = (new PromptController())->show($slug);

        $this->assertSame(200, Response::current()->getStatusCode());
        $this->assertStringContainsString('id="copy-button"', $output);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('slugProvider')]
    public function testPromptPageSetsDebugHashHeader(string $slug): void
    {
        (new PromptController())->show($slug);

        $debugHash = Response::current()->getHeader('X-Debug-Hash');
        $this->assertNotNull($debugHash);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{8}$/', $debugHash);
    }

    public function testPromptPageOmitsSiteChrome(): void
    {
        $output = (new PromptController())->show('1');

        $this->assertStringNotContainsString('<nav', $output);
        $this->assertStringNotContainsString('<footer', $output);
        $this->assertStringNotContainsString('/dist/', $output);
    }

    public function testUnknownSlugReturns404(): void
    {
        $output = (new PromptController())->show('nope');

        $this->assertSame(404, Response::current()->getStatusCode());
        $this->assertStringContainsString('/p/nope', $output);
    }
}
