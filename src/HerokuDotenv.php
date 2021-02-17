<?php

namespace App;

/**
 * Dotenv variables to Heroku environment variables
 */
class HerokuDotenv
{
    /**
     * Heroku app name
     *
     * @var string
     */
    protected $app;

    /**
     * Dotenv filename/path
     *
     * @var string
     */
    protected $dotenvFilename;

    /**
     * Dotenv file contents
     *
     * @var string
     */
    protected $dotenvFileContents;


    /**
     * Stringified .env variables
     *
     * @var string
     */
    protected $dotenvVariables;

    /**
     * Heroku .env
     *
     * @param string $app Heroku app name
     */
    public function __construct(string $app, string $dotenvFilename = ".env")
    {
        $this->app = $app;
        $this->dotenvFilename = $dotenvFilename;
    }

    /**
     * Get .env file content
     *
     * @return bool
     */
    public function getDotenvContent(): bool
    {
        if (!$this->dotenvFileContents = @file_get_contents($this->dotenvFilename)) {
            return false;
        }
        return true;
    }

    /**
     * Push .env variables to Heroku environment variables
     *
     * @return string
     */
    public function push(): string
    {
        return shell_exec("heroku config:set {$this->dotenvVariables} -a {$this->app}");
    }

    /**
     * Pull Heroku environment variables to .env file
     *
     * @return bool
     */
    public function pull()
    {
        $herokuEnvVariables = shell_exec("heroku config -a {$this->app}");

        $dotenvVars = explode("\n", $herokuEnvVariables);

        // Heroku error
        if (strpos($dotenvVars[0], '===')) {
            return false;
        }

        unset($dotenvVars[0]); // Remove first line

        foreach ($dotenvVars as $dotenvVarsKey => $dotenvVar) {
            $vars = explode(': ', $dotenvVar, 2);
            foreach ($vars as $key => $var) {
                if (empty($var)) {
                    unset($dotenvVars[$dotenvVarsKey]); // Remove last line
                } else {
                    $vars[$key] = trim($var);
                    $dotenvVars[$dotenvVarsKey] = implode('=', $vars);
                }
            }
        }
        $herokuDotenvVariables = implode("\n", $dotenvVars);
        return file_put_contents($this->dotenvFilename, $herokuDotenvVariables);
    }

    /**
     * Check .env variables
     *
     * @return bool
     */
    public function checkDotenvVariables(): bool
    {
        $dotenvVars = explode("\n", $this->dotenvFileContents);

        foreach ($dotenvVars as $dotenvVar) {
            if (!empty($dotenvVar)) {
                $dotenvValues = explode('=', $dotenvVar, 2);

                if (!isset($dotenvValues[1])) {
                    return false;
                }
            }
        }

        $this->dotenvVariables = implode(' ', $dotenvVars);
        return true;
    }
}
