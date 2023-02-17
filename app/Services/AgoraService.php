<?php
namespace App\Services;
use App\Libs\Agora\AccessToken;
use App\Libs\Agora\RtmTokenBuilder;
use Log;
use Exception;

class AgoraService
{
    private $appID;
    private $appCertificate;

    public function __construct()
    {
        $this->appID = config('agora.app_id');
        $this->appCertificate = config('agora.app_certificate');
    }

    public function getRtcToken(string $channelName, int $uid = 0, int $expireTimestamp = 0)
    {
        try {
            $builder = AccessToken::init($this->appID, $this->appCertificate, $channelName, $uid);
            $builder->addPrivilege(AccessToken::$Privileges["kJoinChannel"], $expireTimestamp);
            
            return $builder->build();
        } catch (\Exception $e) {
            Log::error('[AGORA_GENERATE_RTC_TOKEN_ERROR] '. $e->getMessage());

            return false;
        }
    }

    public function getRtmToken($Rtm_user_id)
    {
        try {
            $token = RtmTokenBuilder::buildToken($Rtm_user_id);

            return $token;
        } catch (\Exception $e) {
            Log::error('[AGORA_GENERATE_RTM_TOKEN_ERROR] '. $e->getMessage());

            return false;
        }
    }

    //creat token
    public function generateToken($Rtm_user_id)
    {
        try {
            $channelName = 'agora_' . rand(10000,99999);
            // Rtc token dùng để video call

            $token = $this->getRtcToken($channelName);
            // Rtm token dùng để chat

            $rtmToken = $this->getRtmToken($Rtm_user_id);

            if (!$token || !$rtmToken) {
                $this->error('Generate token error');
            }

            $data = [
                'channel_name'  => $channelName,
                'token'         => $token,
                'rtm_token'     => $rtmToken,
            ];

            return collect($data);
        } catch (Exception $e) {
            return 'eror';
        }
    }
}
