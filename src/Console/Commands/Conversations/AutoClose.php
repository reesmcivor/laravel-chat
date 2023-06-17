<?php

namespace ReesMcIvor\Chat\Console\Commands\Conversations;

use App\Models\Tenant;
use Illuminate\Console\Command;
use ReesMcIvor\Auth\Models\TenantUser;
use ReesMcIvor\Chat\Models\Conversation;

use Stancl\Tenancy\Concerns\HasATenantArgument;
use Stancl\Tenancy\Concerns\TenantAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Models\User;

class AutoClose extends Command {

    protected $name = 'chat:conversations:auto_close';
    protected $description = 'Auto closing conversations';

    use TenantAwareCommand;

    public function getTenants()
    {
        return Tenant::all();
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {

        Tenant::all()->each(function($tenant) {
            $tenant->run(function () use ($tenant) {
                Conversation::whereNot('status', 'closed')->get()->each(
                    function(Conversation $conversation) {
                        if($conversation->updated_at->diffInMinutes(now()) > Conversation::getAutoCloseAfterMinutes()) {
                            $conversation->messages()->create([
                                'user_id' => 1,
                                'content' => 'This conversation will be closed in 10 minutes due to inactivity.'
                            ]);
                        }

                    }
                );
            });
        });


        return Command::SUCCESS;

    }

}
