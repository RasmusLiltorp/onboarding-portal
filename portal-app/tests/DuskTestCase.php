<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        if (!static::runningInSail()) {
            static::startChromeDriver(["--port=9515"]);
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $userDataDir =
            sys_get_temp_dir() .
            "/dusk_profile_" .
            (getenv("CI_JOB_ID") ?: getmypid());

        $options = new ChromeOptions()
            ->setBinary(
                "/Applications/Brave Browser.app/Contents/MacOS/Brave Browser",
            )
            ->addArguments(
                collect([
                    $this->shouldStartMaximized()
                        ? "--start-maximized"
                        : "--window-size=1920,1080",
                    "--disable-search-engine-choice-screen",
                    "--disable-smooth-scrolling",
                    "--disable-dev-shm-usage",
                    "--no-sandbox",
                    "--disable-software-rasterizer",
                    "--disable-extensions",
                    "--user-data-dir=" . $userDataDir,
                ])
                    ->unless($this->hasHeadlessDisabled(), function (
                        Collection $items,
                    ) {
                        return $items->merge([
                            "--disable-gpu",
                            "--headless=new",
                        ]);
                    })
                    ->all(),
            );

        return RemoteWebDriver::create(
            $_ENV["DUSK_DRIVER_URL"] ??
                (env("DUSK_DRIVER_URL") ?? "http://localhost:9515"),
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options,
            ),
        );
    }
}
