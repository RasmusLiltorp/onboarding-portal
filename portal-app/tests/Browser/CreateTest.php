<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\URL;

use PHPUnit\Framework\Assert as PHPUnit;


class CreateTest extends DuskTestCase
{

    use DatabaseTruncation;

    protected function setUp(): void{
        parent::setUp();
        $this->artisan('db:seed');
    }


    public function testAccessCreate(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $browser->click('@to-create');
            $browser->assertPathIs('/create');
        });
    }

    public function testFormExist(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $browser->click('@to-create');
            $form = $browser->element('form');
            PHPUnit::assertEquals(
                "post", strtolower($form->getAttribute("method")),
                "Form HTTP method is incorrect. Using: [{$form->getAttribute("method")}]"
            );
            PHPUnit::assertStringEndsWith(
                $browser::$baseUrl, $form->getAttribute("action"),
                "Form HTTP action is incorrect. Using: [{$form->getAttribute("action")}]"
            );
        });
    }

    public function testCreateResource(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $originalCount = count($browser->elements('@element'));
            $browser->click('@to-create');
            $this->formInteraction($browser);
            $browser->press('input[type="submit"]');
            $browser->pause(500);

            $browser->assertPathIs('/');
            $newCount = count($browser->elements('@element'));
            PHPUnit::assertEquals(
                ($originalCount + 1), $newCount,
                "New element is not being displayed on top of original elements"
            );
        });
    }

    public function testSuccessMessage(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $browser->click('@to-create');
            $this->formInteraction($browser);
            $browser->press('input[type="submit"]');
            $browser->pause(500);

            $browser->assertSee('Entity added successfully');
            $browser->refresh();
            $browser->pause(500);
            $browser->assertDontSee('Entity added successfully');
        });
    }

    /**
     * Form interaction for Repository resource
     */
    private function formInteraction(Browser $browser) {
        $browser->type('name', 'test-repository');
        $browser->type('url', 'https://github.com/test/repo');
        $browser->type('description', 'A test repository description');
        $browser->type('guide', 'This is a test onboarding guide with enough content.');
    }
}
