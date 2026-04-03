<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateDataFromPostgres extends Command
{
    protected $signature   = 'db:migrate-from-postgres {--chunk=200 : Rows per batch}';
    protected $description = 'Copy all data from PostgreSQL (pgsql) → MySQL (mysql). Schema must already exist on MySQL.';

    // Tables to skip entirely (system / migration tracking)
    protected array $skipTables = ['migrations', 'telescope_entries', 'telescope_entries_tags', 'telescope_monitoring', 'failed_jobs'];


    public function handle(): int
    {
        $this->info('🔄  Starting PostgreSQL → MySQL data migration...');
        $this->newLine();

        // Verify source (PostgreSQL) connection
        try {
            DB::connection('pgsql')->getPdo();
            $this->info('✅  PostgreSQL connection OK');
        } catch (\Exception $e) {
            $this->error('❌  Cannot connect to PostgreSQL: ' . $e->getMessage());
            return self::FAILURE;
        }

        // Verify target (MySQL) connection
        try {
            DB::connection('mysql')->getPdo();
            $this->info('✅  MySQL connection OK');
        } catch (\Exception $e) {
            $this->error('❌  Cannot connect to MySQL: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->newLine();

        // Get all tables from PostgreSQL (public schema)
        $pgTables = DB::connection('pgsql')
            ->select("SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename");

        $tables = collect($pgTables)->pluck('tablename')->filter(function ($t) {
            return !in_array($t, $this->skipTables);
        })->values();

        $this->info("Found {$tables->count()} tables to migrate.");
        $this->newLine();

        // Disable FK checks on MySQL during migration
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0');
        $this->info('🔓  Foreign key checks disabled on MySQL.');

        $chunk    = (int) $this->option('chunk');
        $success  = 0;
        $failed   = [];

        foreach ($tables as $table) {

            // Check table exists in MySQL target
            if (!Schema::connection('mysql')->hasTable($table)) {
                $this->warn("⏭   Skipping '{$table}' — not found in MySQL schema.");
                continue;
            }

            $this->line("📦  Migrating: <info>{$table}</info>");

            try {
                // Count source rows
                $total = DB::connection('pgsql')->table($table)->count();

                if ($total === 0) {
                    $this->line("     → Empty, skipped.");
                    $success++;
                    continue;
                }

                // Clear existing MySQL data for this table
                DB::connection('mysql')->table($table)->delete();

                $bar = $this->output->createProgressBar($total);
                $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%");
                $bar->start();

                // Copy in chunks
                DB::connection('pgsql')->table($table)->orderByRaw('1')->chunk($chunk, function ($rows) use ($table, $bar) {
                    $data = collect($rows)->map(function ($row) {
                        // Convert stdClass → array and fix PostgreSQL types
                        $arr = (array) $row;
                        foreach ($arr as $key => $value) {
                            // Fix booleans: PostgreSQL returns 't'/'f' strings via PDO
                            if ($value === 't') $arr[$key] = 1;
                            elseif ($value === 'f') $arr[$key] = 0;
                        }
                        return $arr;
                    })->toArray();

                    DB::connection('mysql')->table($table)->insert($data);
                    $bar->advance(count($data));
                });

                $bar->finish();
                $this->newLine();
                $this->line("     ✅  {$total} rows copied.");
                $success++;

            } catch (\Exception $e) {
                $this->newLine();
                $this->error("     ❌  Failed: " . $e->getMessage());
                $failed[] = $table . ': ' . $e->getMessage();
            }
        }

        // Re-enable FK checks
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1');

        $this->newLine();
        $this->info("✅  Migration complete: {$success} tables succeeded.");

        if (!empty($failed)) {
            $this->warn('⚠️  Failed tables:');
            foreach ($failed as $msg) {
                $this->line("   - {$msg}");
            }
        }

        return empty($failed) ? self::SUCCESS : self::FAILURE;
    }
}
