<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateUserDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-domains {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user email domains from @rtech.com/@rtech.test to @store.com/@store.test';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('🔍 Running in DRY-RUN mode - no changes will be made');
            $this->newLine();
        }

        // Find all users with old domains
        $usersToUpdate = User::where('email', 'like', '%@rtech.com')
            ->orWhere('email', 'like', '%@rtech.test')
            ->get();

        if ($usersToUpdate->isEmpty()) {
            $this->info('✓ No users found with old domain (@rtech.com or @rtech.test)');
            $this->info('✓ All users already have correct domains!');
            return Command::SUCCESS;
        }

        $this->info("Found {$usersToUpdate->count()} user(s) to update:");
        $this->newLine();

        $updated = 0;
        $errors = 0;

        foreach ($usersToUpdate as $user) {
            $oldEmail = $user->email;
            $newEmail = str_replace(
                ['@rtech.com', '@rtech.test'],
                ['@store.com', '@store.test'],
                $oldEmail
            );

            if ($isDryRun) {
                $this->line("  [DRY-RUN] {$oldEmail} → {$newEmail}");
            } else {
                try {
                    $user->email = $newEmail;
                    $user->save();
                    $this->info("  ✓ Updated: {$oldEmail} → {$newEmail}");
                    $updated++;
                } catch (\Exception $e) {
                    $this->error("  ✗ Failed: {$oldEmail} - {$e->getMessage()}");
                    $errors++;
                }
            }
        }

        $this->newLine();

        if ($isDryRun) {
            $this->info("DRY-RUN complete. Would update {$usersToUpdate->count()} user(s).");
            $this->info('Run without --dry-run to apply changes.');
        } else {
            $this->info("Update complete!");
            $this->info("✓ Successfully updated: {$updated} user(s)");
            if ($errors > 0) {
                $this->error("✗ Failed: {$errors} user(s)");
                return Command::FAILURE;
            }
        }

        return Command::SUCCESS;
    }
}
