<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class MyConversation extends Conversation
{
    private $name;

    public function sayHello()
    {
        $this->say('Cześć ' . $this->bot->getUser()->getFirstName());
        $this->askForAge();
    }

    public function askForAge()
    {
        $this->ask($this->name . ' Ile masz lat? ', function (Answer $response) {
            if ($this->checkAge($response->getText())) {
               $this->askYesOrNo($response->getText());
           } else {
               $this->say('Proszę o podanie wieku w zakresie 13 do 100 lat.');
           }
        });
    }

    public function askYesOrNo($response)
    {
        $year = $this->calculateYearOfBirth($response);

        $question = Question::create('Dziękuję, Twój rok urodzenia to '. $year . ' ?')
            ->addButtons([
                Button::create('tak')->value('1'),
                Button::create('nie')->value('2'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === '1') {
                    $this->say('Świetnie. Dziękuje za odpowiedź.');
                } else {
                    $this->askForAge();
                }
            }
        });
    }

    public function checkAge($age)
    {
        if ($age < 13 || $age > 100 )
        {
            return false;
        } else {
            return true;
        }
    }

    public function calculateYearOfBirth($age)
    {
        $currentYear = date('Y');
        $reply = $currentYear - intval($age);
        return $reply;
    }

    /**
     * Start the conversation.
     */
    public function run()
    {
        $this->sayHello();
    }
}
