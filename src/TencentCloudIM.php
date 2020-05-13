<?php

namespace Luoying\TencentCloudIM;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Tencent\TLSSigAPIv2;

class TencentCloudIM {

    private const SDK_APP_ID = '1400361273';

    private const SECRET_KEY = 'f33045b36457e9591ab94d4fc7ed9f746398b2ecb6dd0a70e79a9b2bd2952060';

    public static function getUserSig()
    {
        $api = new TLSSigAPIv2(self::SDK_APP_ID, self::SECRET_KEY);

        try {

            $sig = $api->genSig('admin');

        } catch (\Exception $e) {

            return $e;
        }

        return $sig;
    }

    /**
     * 发送自定义消息
     * @return string
     */
    public static function sendMsg()
    {

        try {
            # 请求令牌
            $http = new Client(['base_uri' => 'https://console.tim.qq1.com']);

            # 生成userSig
            $sig = self::getUserSig();

            # 设置查询参数
            $query = [
                'sdkappid' => self::SDK_APP_ID,
                'identifier' => 'admin',
                'usersig' => $sig,
                'random' => time(),
                'contenttype' => 'json'
            ];

            $response = $http->request('POST', "v4/group_open_http_svc/send_group_system_notification", [
                'query' => $query,
                'json' => [
                    "GroupId" => '5',
                    "Content" => 'Hello My Composer Test Project!'
                ]
            ]);

            return $response->getBody()->getContents();
        }catch (RequestException $e) {
            print_r($e->getRequest());
            if ($e->hasResponse()) {
                print_r($e->getResponse());
            }
        }catch (ServerException $e) {

        }catch (ClientException $e) {

        }

//        if ($res['ActionStatus'] == 'OK') {
//
//            return [
//                'code' => 200,
//                'msg' => 'ok',
//                'data' => $Content
//            ];
//        } else {
//
//            throw new WebFormException($res['ErrorInfo'], 422);
//        }
    }
}