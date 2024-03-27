<?php

namespace ReesMcIvor\Chat\Http\Livewire;

use Livewire\Component;
use ReesMcIvor\Chat\Models\Conversation;
use ReesMcIvor\Chat\Models\Message;

class ViewConversation extends Component
{

    public $message = '';
    public $messages = [];

    protected $listeners = ['updateMessages' => 'updateMessages'];

    public function mount( Conversation $conversation)
    {
        $this->conversation = $conversation;
        $this->updateMessages();
    }

    public function updateMessages()
    {
        $this->conversation->refresh();
        $this->messages = $this->conversation->messages->mapWithKeys(function($message, $index) {
            return [$message->id => ['content' => $message->content]];
        })->toArray();
        $this->emit('saved');
    }

    public function delete( Message $message )
    {
        $message->delete();
        $this->updateMessages();
        $this->emit('saved');
    }

    public function updateMessage( Message $message )
    {
        $message->update(['content' => $this->messages[$message->id]['content']]);
        $this->updateMessages();
        $this->emit('saved');
    }

    public function save()
    {
        $this->conversation->messages()->create([
            'user_id' => auth()->id(),
            'content' => $this->message
        ]);
        $this->message = '';
        $this->updateMessages();
        $this->emit('saved');

    }

    public function render()
    {
        $this->conversation->refresh();
        return view('chat::livewire.conversation.view')
            ->layout('layouts.app', ['header' => 'Conversation with : ' . $this->conversation->getParticipantNames() ]);
    }

}
