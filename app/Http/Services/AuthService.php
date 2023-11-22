<?php

namespace App\Http\Services;

use App\Http\DTOs\TokenDTO;
use App\Models\Token;
use App\Repositories\Token\TokenRepository;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;

class AuthService
{
    public function __construct(UserRepository $userRepository, TokenRepository $tokenRepository)
    {
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
    }

    public function authenticate($email, $pass): ?TokenDTO
    {
        $user = $this->userRepository->authenticate($email, $pass);

        if ($user !== null) {
            $token = new Token();
            $token->UserId = $user->id;
            $token->CreatedAt = now();
            $token->TKey = uniqid();

            //$token->save();

            if ($token->save()) {
                $resDTO = self::convert($token);
                $resDTO->UserId = $user->id;
                $resDTO->UserEmail = $user->Email;
                $resDTO->UserTypeId = $user->UserTypeId;

                return $resDTO;
            }
        }

        return null;
    }

    public function isTokenValid($tkey)
    {
        $token = $this->tokenRepository->findByName($tkey);
        if ($token !== null && ($token->ExpiredAt==null)) {
            return true;
        }

        return false;
    }

    public function logOut($tkey): bool
    {
        $token = $this->tokenRepository->findByName($tkey);

        if ($token !== null) {
            $token->ExpiredAt = Carbon::now();
            $token->save();

            return true;
        }

        return false;
    }



    public static function convert($token): TokenDTO
    {
        $tokenDTO = new TokenDTO();
        $tokenDTO->CreatedAt = $token->CreatedAt;
        $tokenDTO->ExpiredAt = $token->ExpiredAt;
        $tokenDTO->TKey = $token->TKey;
        $tokenDTO->UserId = 0;

        return $tokenDTO;
    }
}
