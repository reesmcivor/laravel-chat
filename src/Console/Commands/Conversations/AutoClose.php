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

    /* use TenantAwareCommand; */

    public function getTenants()
    {
        return Tenant::all();
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        Conversation::whereNot('status', 'closed')->get()->each(function(Conversation $conversation) {
            if($conversation->isClosableAfterLeniency()) {
                $conversation->close();
            } elseif ($conversation->isClosable()) {
                $conversation->sendAuthCloseWarningMsg();
            }
        });

        return Command::SUCCESS;

    }

}
