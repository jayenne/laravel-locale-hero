<?php

namespace Jayenne\LaravelLocaleHero\Tests\Unit;

use Jayenne\LaravelLocaleHero\Tests\TestCase;

class MiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set config variables
        $this->app['config']->set('locale-hero.fallback', 'en-GB');
        $this->app['config']->set('locale-hero.allowed', [
            'nl-NL',
            'nl-BE',
            'en-GB',
            'en-US',
        ]);
    }

    /** @test */
    public function sessions_will_be_set_on_first_visit_with_fallback()
    {
        $this->get('test_route', ['HTTP_ACCEPT_LANGUAGE' => 'gr,zh-CH'])
            ->assertStatus(200);

        $this->assertEquals('en-GB', session('locale_code'));
        $this->assertEquals('en', session('locale'));
    }

    /** @test */
    public function sessions_will_be_set_on_first_visit_according_to_browser()
    {
        $this->get('test_route', ['HTTP_ACCEPT_LANGUAGE' => 'nl-BE'])
            ->assertStatus(200);

        $this->assertEquals('nl-BE', session('locale_code'));
        $this->assertEquals('nl', session('locale'));
    }
}
