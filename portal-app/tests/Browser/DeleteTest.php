<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use PHPUnit\Framework\Assert as PHPUnit;

class DeleteTest extends DuskTestCase
{
    use DatabaseTruncation;

    protected function setUp(): void{
        parent::setUp();
        $this->artisan('db:seed');
    }


    public function testAccessDelete(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $browser->press('@to-delete');
            $browser->pause(500);
            $browser->assertPathIs('/');
        });
    }

    public function testDeleteFormExist(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $form = $browser->element('form');
            PHPUnit::assertEquals(
                "post", strtolower($form->getAttribute("method")),
                "Form HTTP method is incorrect. Using: [{$form->getAttribute("method")}]"
            );

            $method = $browser->inputValue('_method');

            PHPUnit::assertTrue(
                "DELETE" == $method,
                "Form HTTP method is incorrect."
            );
            PHPUnit::assertStringEndsWith(
                "/1", $form->getAttribute("action"),
                "Form HTTP action is incorrect. Using: [{$form->getAttribute("action")}]"
            );
        });
    }

    public function testDeleteElement(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $originalCount = count($browser->elements('@element'));
            $browser->press('@to-delete');
            $browser->pause(500);

            $browser->assertPathIs('/');
            $newCount = count($browser->elements('@element'));
            PHPUnit::assertEquals(
                ($originalCount - 1), $newCount,
                "Element was not removed from the list"
            );
        });
    }

    public function testDeleteMessage(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $browser->press('@to-delete');
            $browser->pause(500);
            $browser->assertPathIs('/');

            $browser->assertSee('Entity deleted successfully');
            $browser->refresh();
            $browser->pause(500);
            $browser->assertDontSee('Entity deleted successfully');
        });
    }

}
