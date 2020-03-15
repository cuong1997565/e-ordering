<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function failnow(...$messages)
    {
        $message_err = '';
        foreach ($messages as $message) {
            if (is_string($message)) {
                $message_err = $message_err . $message . ', ';
            }
            if (is_object($message) && get_class($message) == \App\Models\Error::class) {
                $message_err = $message_err . $message->toError();
            }
        }
        return $this->fail($message_err);
    }
}
