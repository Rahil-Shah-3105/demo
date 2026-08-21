<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Event;

use App\Entity\Comment;
use Symfony\Contracts\EventDispatcher\Event;

final class CommentCreatedEvent extends Event
{
    public function __construct(
        protected Comment $comment,
    ) {
	error_log(sprintf('>>> Entering __construct($comment=%s)', is_scalar($comment) ? $comment : gettype($comment)));
    }

    public function getComment(): Comment
    {
		error_log(sprintf('>>> Entering getComment()'));
        return $this->comment;
    }
}
