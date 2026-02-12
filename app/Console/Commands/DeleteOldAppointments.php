<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class DeleteOldAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yesterday = Carbon::yesterday()->toDateString();

        Appointment::where('date', '<', $yesterday)->delete();

        $this->info('Oude afspraken verwijderd.');
    }
}
