<?php

namespace AppBundle\Security;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostPoint;
use AppBundle\Entity\User;
use AppBundle\Entity\Coment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class UserVoter extends Voter
{
    const EDIT = 'edit';
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::EDIT))) {
            return false;
        }

        if (!($subject instanceof Post || $subject instanceof Coment || $subject instanceof PostPoint)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $userBloger = $this->decisionManager->decide($token, array('ROLE_USER_BLOGER'));

        if ($attribute == self::EDIT && $subject instanceof Post && $userBloger) {
            return $this->canEditPost($subject, $user);
        } elseif ($attribute == self::EDIT && ($subject instanceof Coment || $subject instanceof PostPoint)) {
            return $this->canEditPostAdditions($subject, $user);
        } else {
            return false;
        }
    }

    private function canEditPost(Post $post, User $user)
    {
        return $user === $post->getUser();
    }

    private function canEditPostAdditions($subject, User $user)
    {
        return $user === $subject->getUser();
    }
}
