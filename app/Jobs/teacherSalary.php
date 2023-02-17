<?php

namespace App\Jobs;

use App\Models\Available_class;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class teacherSalary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $teachers;
    public function __construct($teachers)
    {
        $this->teachers = $teachers;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->teachers as $teacher){
            $teacher->balance += $teacher->startNotPay->count() * 2; //every class 2 in balance
            $teacher->save();
            Available_class::where('teacher_id', $teacher->id)->startNotPay()->update(['teacher_mony' => 1]);
        }
    }
}
