<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use PHPUnit\Framework\Assert as PHPUnit;

class UpdateTest extends DuskTestCase
{
    use DatabaseTruncation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan("db:seed");
    }

    public function testAccessUpdate(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit("/");
            $browser->click("@to-edit");
            $browser->assertPathIs("/1/edit");
        });
    }

    public function testUpdateFormExist(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit("/1/edit");
            $form = $browser->element("form");
            PHPUnit::assertEquals(
                "post",
                strtolower($form->getAttribute("method")),
                "Form HTTP method is incorrect. Using: [{$form->getAttribute(
                    "method",
                )}]",
            );

            $method = $browser->inputValue("_method");

            PHPUnit::assertTrue(
                "PUT" == $method || "PATCH" == $method,
                "Form HTTP method is incorrect.",
            );
            PHPUnit::assertStringEndsWith(
                "/1",
                $form->getAttribute("action"),
                "Form HTTP action is incorrect. Using: [{$form->getAttribute(
                    "action",
                )}]",
            );
        });
    }

    /**
     * Test that edit form is prefilled with resource data
     */
    public function testUpdateFormFilled(): void
    {
        $this->browse(function (Browser $browser) {
            $model_name = env("ASSIGNMENT_RESOURCE");
            $model = app("App\Models\\" . $model_name);
            $resource = $model::find(1);

            $browser->visit("/1/edit");
            $browser->assertInputValue("name", $resource->name);
            $browser->assertInputValue("url", $resource->url);
        });
    }

    /**
     * Test that updating a resource works
     */
    public function testUpdateInformation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit("/1/edit");

            $browser->type("name", "updated-repository-name");

            $browser->script(
                'document.querySelector(\'input[type="submit"]\').click()',
            );
            $browser->waitForLocation("/");

            $browser->assertPathIs("/");
            $browser->assertSee("updated-repository-name");
        });
    }

    /**
     * Test success message after update
     */
    public function testUpdateMessage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit("/1/edit");

            $browser->type("name", "updated-repo");

            $browser->script(
                'document.querySelector(\'input[type="submit"]\').click()',
            );
            $browser->waitForLocation("/");

            $browser->assertPathIs("/");
            $browser->assertSee("Entity updated successfully");
            $browser->refresh();
            $browser->pause(500);
            $browser->assertDontSee("Entity updated successfully");
        });
    }
}
