<?php


namespace App\Security;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class IndexVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';
    const ADD_TO_BASKET = 'add';

    protected function supports($attribute, $subject)
    {
         if(!in_array($attribute, [self::EDIT, self::DELETE, self::ADD_TO_BASKET])){
             return false;
         }

         if(!$subject instanceof Product){
             return false;
         }
         return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $authenticatedUser = $token->getUser();

        if(!$authenticatedUser instanceof  User) {
            return false;
        }

        /** @var Product $product */
        $product = $subject;

        if($attribute == 'add'){
            if($product->getUser()->getId() === $authenticatedUser->getId())
            {
                return false;
            } else {
                return true;
            }
        }
        return $product->getUser()->getId() === $authenticatedUser->getId();
    }
}