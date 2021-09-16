<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Mail;
use SebastianBergmann\Environment\Console;

class SendReminderEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;



    const EmailNotifyAdmin = 'emailNotifyAdmin';

    protected $function;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($callback,Array $data = [])
    {
        //
        $this->function = $callback;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()    
    {
        //
       
       // $f = $this->function;
       //$f();
       [$this,$this->function]();
    }


    private function emailNotifyAdmin(){
        $data = $this->data;
        Log::debug('data job email',$data);
        $res =  Mail::send('emails.text',['text'=>$data['text']], function ($m) use ($data) {
            //$m->from("example@gmail.com", 'Fútbol8 Alem');
            $m->to($data['user']->email, $data['user']->email)->subject($data['subject']);
            //$m->later($when);
        });

        Log::info('send Email to '. $data['user']->completeName . ' ' . $data['user']->email ,[$res]);
    }
}
