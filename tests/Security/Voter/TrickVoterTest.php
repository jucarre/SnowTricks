<?php


namespace App\Tests\Security\Voter;


use App\Entity\Trick;
use App\Entity\User;
use App\Security\Voter\TrickVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TrickVoterTest extends TestCase
{
    /**
     * @dataProvider voterProvider
     */
    public function testTrickVoter($user, $expected)
    {
        $voter = new TrickVoter();

        $trick = new Trick();

        $token = new AnonymousToken('secret', 'anonymous');

        if ($user) {
            $token = new UsernamePasswordToken($user, 'credentials', 'memory');
            $trick->setUser($user);
        }

        $this->assertSame($expected, $voter->vote($token, $trick, ['EDIT']));
    }

    public function voterProvider()
    {
        $userOne = $this->createMock(User::class);
            $userOne->method('getId')->willReturn(1);
        return [
            [$userOne, 1],
            [null, -1]
        ];
    }
}