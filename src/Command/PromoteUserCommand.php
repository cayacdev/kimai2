<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Entity\User;
use App\User\UserService;
use Symfony\Component\Console\Style\SymfonyStyle;

class PromoteUserCommand extends AbstractRoleCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('kimai:user:promote')
            ->setAliases(['fos:user:promote'])
            ->setDescription('Promotes a user by adding a role')
            ->setHelp(
                <<<'EOT'
The <info>kimai:user:promote</info> command promotes a user by adding a role

  <info>php %command.full_name% susan_super ROLE_TEAMLEAD</info>
  <info>php %command.full_name% --super susan_super</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function executeRoleCommand(UserService $manipulator, SymfonyStyle $output, User $user, bool $super, $role)
    {
        $username = $user->getUsername();
        if ($super) {
            if (!$user->isSuperAdmin()) {
                $user->setSuperAdmin(true);
                $manipulator->updateUser($user);
                $output->success(sprintf('User "%s" has been promoted as a super administrator.', $username));
            } else {
                $output->warning(sprintf('User "%s" does already have the super administrator role.', $username));
            }
        } else {
            if (!$user->hasRole($role)) {
                $user->addRole($role);
                $manipulator->updateUser($user);
                $output->success(sprintf('Role "%s" has been added to user "%s".', $role, $username));
            } else {
                $output->warning(sprintf('User "%s" did already have "%s" role.', $username, $role));
            }
        }
    }
}
