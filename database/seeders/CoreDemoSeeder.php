<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\Invoice;
use App\Models\Note;

/**
 * Seeder for CRM core demo data.
 */
class CoreDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1) Clients
        $clients = Client::factory(10)->create();

        // 2) Projects per client
        $projects = collect();

        foreach ($clients as $client) {
            $created = Project::factory(rand(1, 3))->create([
                'client_id' => $client->id,
            ]);

            $projects = $projects->merge($created);
        }

        // 3) Tasks per project
        foreach ($projects as $project) {
            Task::factory(rand(3, 8))->create([
                'project_id' => $project->id,
            ]);
        }

        // 4) Invoices per client
        foreach ($clients as $client) {
            Invoice::factory(rand(1, 2))->create([
                'client_id' => $client->id,
            ]);
        }

        // 5) Notes sprinkled across entities
        $clients->each(function (Client $client): void {
            Note::factory(rand(0, 2))->forNotable($client)->create();
        });

        $projects->each(function (Project $project): void {
            Note::factory(rand(0, 2))->forNotable($project)->create();
        });

        Task::inRandomOrder()
            ->take(10)
            ->get()
            ->each(function (Task $task): void {
                Note::factory()->forNotable($task)->create();
            });

        Invoice::inRandomOrder()
            ->take(8)
            ->get()
            ->each(function (Invoice $invoice): void {
                Note::factory()->forNotable($invoice)->create();
            });
    }
}
