<?php

namespace Rseon\Mallow\Controllers\Traits;

trait Header
{
    protected $headers = [];

    /**
     * Display the headers
     */
    protected function displayHeaders()
    {
        foreach($this->headers as $k => $v) {
            header("$k: $v");
        }
    }

    /**
     * Add an header
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    protected function addHeader(string $key, string $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * Set headers
     *
     * @param array $headers
     * @return $this
     */
    protected function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * Set default headers
     */
    protected function setDefaultHeaders()
    {
        $headers = [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Expect-CT' => 'enforce,max-age=30',
            'Referrer-Policy' => 'no-referrer',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
        ];

        if(is_xhr()) {
            $headers['Content-Type'] = 'application/json';
        }

        $this->setHeaders($headers);
    }
}