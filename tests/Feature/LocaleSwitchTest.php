<?php

namespace Jayenne\LaravelLocaleHero\Tests\Unit;

use Illuminate\Foundation\Auth\User;
use Jayenne\LaravelLocaleHero\Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Set config variables
        $this->app['config']->set('locale-hero.fallback', 'en-GB');
        $this->app['config']->set(
            'locale-hero.allowed',
            [
            'nl-NL',
            'nl-BE',
            'en-GB',
            'en-US'
            ]
        );

        User::unguard();
        $this->user = User::create(
            [
            'name' => 'Orchestra',
            'email' => 'hello@orchestraplatform.com',
            'password' => \Hash::make('456'),
            'locale_code' => null,
            ]
        );
        User::reguard();
    }

    /** @test */
    public function switch_for_non_logged_in_user()
    {
        // make first visit and verify fallback values are set
        $this->get('test_route', ['HTTP_ACCEPT_LANGUAGE' => 'gr,zh-CH'])
            ->assertStatus(200);
        $this->assertEquals('en-GB', session('locale_code'));
        $this->assertEquals('en', session('locale'));

        // visit switch route
        $this->get(
            route(
                'locale_hero.switch',
                ['locale_code' => 'nl-BE']
            ),
            ['HTTP_REFERER' => 'test_route']
        )
            ->assertRedirect(route('test_route'));
        $this->assertEquals('nl-BE', session('locale_code'));
        $this->assertEquals('nl', session('locale'));
    }

    /**
     * @test
     * @return [type] [description]
     */
    public function switch_for_logged_in_user_without_locale_setting()
    {
        // make first visit and verify fallback values are set
        $this->actingAs($this->user)->get(
            'test_route',
            ['HTTP_ACCEPT_LANGUAGE' => 'gr,zh-CH']
        )
            ->assertStatus(200);
        $this->assertEquals('en-GB', session('locale_code'));
        $this->assertEquals('en', session('locale'));

        // visit switch route
        $this->get(
            route(
                'locale_hero.switch',
                ['locale_code' => 'nl-BE']
            ),
            ['HTTP_REFERER' => 'test_route']
        )
            ->assertRedirect(route('test_route'));
        $this->assertEquals('nl-BE', session('locale_code'));
        $this->assertEquals('nl', session('locale'));

        $this->assertEquals('nl-BE', $this->user->locale_code);
    }
}
