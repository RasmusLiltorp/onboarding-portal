<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use PHPUnit\Framework\Assert as PHPUnit;
use Facebook\WebDriver\WebDriverBy;

class IndexTest extends DuskTestCase
{
    use DatabaseTruncation;

    protected function setUp(): void{
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testListResources(): void
    {
        $this->browse(function (Browser $browser) {
            $model_name = env("ASSIGNMENT_RESOURCE");
            $model = app("App\Models\\".$model_name);
            $browser->visit('/');
            $browser->assertCount('@element', $model::count());
        });
    }

    public function testShowLink() : void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            foreach($browser->elements('@element') as $element) {
                PHPUnit::assertEquals(
                    1,
                    count($element->findElements(WebDriverBy::cssSelector($browser->resolver->format('@to-show')))),
                    "There is no single link to go to Show Page in element [{$element->getText()}]"
                );
            }
        });
    }

    public function testEditLink() : void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            foreach($browser->elements('@element') as $element) {
                PHPUnit::assertEquals(
                    1,
                    count($element->findElements(WebDriverBy::cssSelector($browser->resolver->format('@to-edit')))),
                    "There is no single link to go to Edit Page in element [{$element->getText()}]"
                );
            }
        });
    }

    public function testDeleteLink() : void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            foreach($browser->elements('@element') as $element) {
                PHPUnit::assertEquals(
                    1,
                    count($element->findElements(WebDriverBy::cssSelector($browser->resolver->format('@to-delete')))),
                    "There is no single link to remove element [{$element->getText()}]"
                );
            }
        });
    }

    public function testCreateLink() : void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            PHPUnit::assertEquals(
                1,
                count($browser->elements('@to-create')),
                "There is no link to the Create Page"
            );
        });
    }
}
