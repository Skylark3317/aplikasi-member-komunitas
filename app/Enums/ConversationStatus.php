<?php

namespace App\Enums;

enum ConversationStatus: string
{
    case Unanswered = 'unanswered';
    case Answered = 'answered';
    case Resolved = 'resolved';
}
