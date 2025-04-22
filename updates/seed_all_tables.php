<?php namespace SpAnjaan\Support\Updates;

use Winter\Storm\Database\Updates\Seeder;
use DB;

/**
 * Class SeedAllTables
 * @package SpAnjaan\Support\Updates
 */
class SeedAllTables extends Seeder
{

    /**
     *
     */
    public function run()
    {
        $this->seedPriorities();
        $this->seedStatuses();
        $this->seedCategories();
    }

    private function seedPriorities()
    {
        $table = 'spanjaan_support_ticket_priorities';
        $records = [
            ['name' => 'Low'],
            ['name' => 'Medium'],
            ['name' => 'High'],
            ['name' => 'Critical'],
            ['name' => 'Paid'],
        ];


        DB::beginTransaction();

        try {
            DB::table($table)->insert($records);
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e); //todo
        }

        DB::commit();
    }

    private function seedStatuses()
    {
        $table = 'spanjaan_support_ticket_statuses';
        $records = [
            ['name' => 'New'],
            ['name' => 'Assigned'],
            ['name' => 'Awaiting Feedback'],
            ['name' => 'Reported to developers'],
            ['name' => 'Pending'],
            ['name' => 'Rejected'],
            ['name' => 'Cancelled'],
            ['name' => 'Resolved'],
            ['name' => 'Closed'],
        ];


        DB::beginTransaction();

        try {
            DB::table($table)->insert($records);
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e); //todo
        }

        DB::commit();
    }

    private function seedCategories()
    {
        $table = 'spanjaan_support_ticket_categories';
        $records = [
            ['name' => 'Service Issue'],
            ['name' => 'Webdesign'],
            ['name' => 'Feature Request'],
            ['name' => 'Other'],
        ];


        DB::beginTransaction();

        try {
            DB::table($table)->insert($records);
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e); //todo
        }

        DB::commit();
    }

}
