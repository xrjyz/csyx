<?php

namespace app\index\controller;
use app\common\controller\Api;
use think\Route;
use think\Db;

class Login extends Api
{

    public function index()
    {
        // $this->error('no access');
        $this->msg($this->user);
    }

    //注册
    public function reg(){
        if (is_post()) {
            $da = input('post.');
            //验证器
            $validate = validate('User');
            $res = $validate->scene('homeadd')->check($da);
            if (!$res) {
                $this->e_msg($validate->getError());
            }
            $code_info = cache($da['us_tel'] . 'code') ?: "";
            if (!$code_info) {
                $this->e_msg('请重新发送验证码');
            } elseif ($da['sode'] != $code_info) {
                $this->e_msg('验证码不正确');
            }
            //父账号
            // if ($da['p_vip_account'] != '') {
            //     $pinfo = model("User")->where('us_account', $da['p_vip_account'])->find();
            //     if ($pinfo) {
            //         $da['us_pid'] = $pinfo['id'];
            //         $da['us_path'] = $pinfo['us_path'] . $pinfo['id'].',';
            //         $da['us_path_long'] = $pinfo['us_path_long'] + 1;
            //     } else {
            //         $this->e_msg('推荐人不存在');
            //     }
            // }else{
            //     $this->e_msg('推荐人不存在');
            // }
            $rel = model('User')->tianjia($da);
            $this->msg($rel);
        }
    }
    //忘记密码
    public function forget(){
        
        if (is_post()) {
            $post = input('post.');
            $validate = validate('User');
            $res = $validate->scene('pass')->check($post);
            if (!$res) {
                $this->e_msg($validate->getError());
            }

            $code_info = cache($post['us_tel'] . 'code') ?: "";
            if (!$code_info) {
                $this->e_msg('请重新发送验证码');
            } elseif ($post['sode'] != $code_info) {
                $this->e_msg('验证码不正确');
            }
            $info = Db::name('user')->where('us_tel',$post['us_tel'])->find();
            if(!$info){
                $this->e_msg('该手机号未注册');
            }
            $arr = array_merge($post,['id'=>$info['id']]);
            $rst  = model('User')->homeEdit($arr);
            $this->msg($rst);

        }else{
            $this->e_msg('get');
        }
    }
    public function fg(){
        $post = input('post.');
        if ($post) {

            $validate = validate('User');
            $res = $validate->scene('pass')->check($post);
            if (!$res) {
                $this->e_msg($validate->getError());
            }
           
            $info = db('user')->where('us_tel',$post['us_tel'])->find();
            if(!$info){
                $this->e_msg('该用户不存在');
            }
            $code_info = cache($post['us_tel'] . 'code') ?: "";
            if (!$code_info) {
                $this->e_msg('请重新发送验证码');
            } elseif ($post['sode'] != $code_info) {
                $this->e_msg('验证码不正确');
            }

            $arr = array_merge($post,['id'=>$info['id']]);
            $rst  = model('User')->editInfo($arr);
            $this->s_msg($rst);

        }else{
            $this->e_msg();
        }
    }
   
}