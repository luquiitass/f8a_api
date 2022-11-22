<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Massaging;
use App\Models\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Illuminate\Support\Facades\Gate;


class ProcessNotifications extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        //
        //Log::info("creo job  de Notificacion ");
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Log::info("Envio Notificacion desde el Job");
        $this->message->sendTo();
        
    }
}
