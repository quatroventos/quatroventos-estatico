<?php

namespace App\Policies;

use App\Models\AiVisibilityReport;
use App\Models\User;

class AiVisibilityReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isCmsMember();
    }

    public function view(User $user, AiVisibilityReport $aiVisibilityReport): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, AiVisibilityReport $aiVisibilityReport): bool
    {
        return false;
    }

    public function delete(User $user, AiVisibilityReport $aiVisibilityReport): bool
    {
        return $user->isCmsMember();
    }
}
