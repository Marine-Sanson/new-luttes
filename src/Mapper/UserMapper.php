<?php

namespace App\Mapper;

use App\Entity\User;
use App\Model\UserForList;

class UserMapper
{
    public function transformToUserForList(User $user): UserForList
    {
        $userForList = (new UserForList())
            ->setId($user->getId())
            ->setEmail($user->getEmail())
            ->setName($user->getName())
            ->setAgreement($user->getAgreement())
        ;

        if($user->getTel()){
            $userForList->setTel($user->getTel());
        }

        if($user->getPhoto()){
            $userForList->setPhotoName($user->getPhoto()->getName());
        }

        return $userForList;
    }

}
