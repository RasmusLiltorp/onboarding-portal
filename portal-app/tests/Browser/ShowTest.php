<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ShowTest extends DuskTestCase
{
    use DatabaseTruncation;

    protected function setUp(): void{
        parent::setUp();
        $this->artisan('db:seed');
    }


    public function testAccessShow(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $browser->click('@to-show');
            $browser->assertPathIs('/1');
        });
    }

    /**
     * Test that different resources show different information
     */
    public function testDifferentResources(): void
    {
        $this->browse(function (Browser $browser) {
            $model_name = env("ASSIGNMENT_RESOURCE");
            $model = app("App\Models\\".$model_name);
            $resource1 = $model::find(1);
            $resource2 = $model::find(2);

            $browser->visit('/1');
            $browser->assertSee($resource1->name);

            $browser->visit('/2');
            $browser->assertSee($resource2->name);
        });
    }

    /**
     * Test that resource info is displayed on show page
     */
    public function testResourceInfo(): void
    {
        $this->browse(function (Browser $browser) {
            $model_name = env("ASSIGNMENT_RESOURCE");
            $model = app("App\Models\\".$model_name);
            $resource = $model::find(1);
            $browser->visit('/1');

            $browser->assertSee($resource->name);
            $browser->assertSee($resource->description);
        });
    }
}
