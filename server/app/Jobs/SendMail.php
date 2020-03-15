<?php

namespace App\Jobs;

use App\Models\MailTemplate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Engines\PhpEngine;

class SendMail extends Job
{
    var $templateID;
    var $headData;
    var $contentData;

    /**
     * Send email using background process
     * @param $templateID int : Hardcode ID from database
     * @param $headData array : Header email data
     * @param $contentData array : The params pass to the content of the template
     * @return void
     */
    public function __construct($templateID,$headData,$contentData=[])
    {
        /* Example of data
        $templateID = 1; // The template id from database
        $headData =
        [
            'to'=>'member@gmail.com',
            'subject'=>'This is email from admin'
        ];

        $contentData =
        [
            'name' => 'Member',
            'email'=> 'member@gmail.com',
            'age'=> 30
        ]; */

        $this->templateID = $templateID;
        $this->headData = $headData;
        $this->contentData = $contentData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Comment this line to load from file instead of from database
        Storage::disk('local')->delete('/email/'.$this->templateID.'.php');
        $content = null;

        // Check to load from cache
        $phpEngine = new PhpEngine();
        if(Storage::disk('local')->has('/email/'.$this->templateID.'.php'))
        {
            $content = $phpEngine->get(storage_path().'/app/email/'.$this->templateID.'.php',$this->contentData);
        }
        else
        {
            $bladeTemplate = MailTemplate::find($this->templateID);
            if($bladeTemplate)
            {
                $bladeTemplate = $bladeTemplate->content;

                $phpTemplate = Blade::compileString($bladeTemplate);
                Storage::disk('local')->put('/email/'.$this->templateID.'.php', $phpTemplate);
                $content = $phpEngine->get(storage_path().'/app/email/'.$this->templateID.'.php',$this->contentData);
            }
        }

        if(!empty($content))
        {
            Mail::send('mail', ['content'=>$content], function ($message)
            {
                $message->to($this->headData['to']);
                $message->subject($this->headData['subject']);
            });
        }
    }
}
