<?php

namespace Rseon\Mallow\Command;

class Keygen extends AbstractCommand
{

    const MIN = 32;

    protected $help = <<<HELP
[Usage] php console keygen

[Options]
    l | length Set key length (16 minimum)
HELP;

    protected $aliases = [
        'length' => 'l',
    ];

    public function handle($action = null)
    {
        $length = (int) $this->getOption('length');
        if($length < static::MIN) {
            $length = static::MIN;
        }
        
        $this->print();
        $this->print('Paste this APP_KEY into your .env file :');
        $this->print();
        $this->print(token($length));
        $this->print();
    }
}